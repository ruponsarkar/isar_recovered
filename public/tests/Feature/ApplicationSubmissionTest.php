<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApplicationSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private string $uploadRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uploadRoot = sys_get_temp_dir().'/isar-submission-test-'.Str::uuid();
        $this->app->instance('path.public', $this->uploadRoot);
        config(['services.public_api.base_url'=>'']);
        Schema::create('journals', function (Blueprint $table) {
            $table->increments('j_id'); $table->string('slug'); $table->string('j_name'); $table->integer('active'); $table->integer('sl_no');
        });
        \Illuminate\Support\Facades\DB::table('journals')->insert([
            ['slug'=>'isarjahss', 'j_name'=>'Arts Journal', 'active'=>1, 'sl_no'=>1],
            ['slug'=>'science', 'j_name'=>'Science Journal', 'active'=>1, 'sl_no'=>2],
            ['slug'=>'inactive', 'j_name'=>'Inactive Journal', 'active'=>0, 'sl_no'=>3],
        ]);
        Schema::create('manuscript', function (Blueprint $table) {
            $table->increments('m_id');
            foreach (['mode', 'type', 'journal', 'author', 'country', 'affiliation', 'email', 'mobile', 'manuscript', 'file'] as $column) {
                $table->text($column);
            }
        });
        foreach (['authors', 'reviewers', 'editors'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->increments('id');
                foreach (['name', 'email', 'mobile', 'country', 'specialization', 'affiliation', 'publication', 'cv', 'photo'] as $column) {
                    $table->text($column);
                }
            });
        }
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->uploadRoot);
        parent::tearDown();
    }

    private function payer(): array
    {
        return ['name'=>'Test Author', 'email'=>'author@example.com', 'country'=>'India',
            'affiliation'=>'Test University', 'phone'=>'1234567890'];
    }

    public function test_submit_links_preserve_journal_context(): void
    {
        $html = view('layouts.app', ['journal'=>['slug'=>'science', 'j_id'=>2, 'j_name'=>'Science Journal']])->render();
        $this->assertStringContainsString('/manuscript?journal=science', $html);
        $html = view('layouts.app')->render();
        $this->assertStringNotContainsString('/manuscript?journal=', $html);
    }

    public function test_manuscript_journal_selection_and_inactive_validation(): void
    {
        $this->get('/manuscript?journal=science')->assertOk()
            ->assertSee('value="science" selected', false)->assertSee('Arts Journal')->assertDontSee('Inactive Journal');
        $this->get('/manuscript?journal=inactive')->assertOk()->assertDontSee('value="inactive"', false);
        $this->from('/manuscript')->post('/manuscript', $this->payer() + [
            'mode'=>'Normal Process', 'article_type'=>'Research article', 'journal'=>'inactive',
            'title'=>'Test Research', 'file'=>UploadedFile::fake()->create('paper.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('journal');
        $this->assertDatabaseCount('manuscript', 0);
        $this->withSession(['_old_input'=>['journal'=>'isarjahss']])->get('/manuscript?journal=science')
            ->assertOk()->assertSee('value="isarjahss" selected', false)->assertDontSee('value="science" selected', false);
    }

    public function test_manuscript_is_saved_with_legacy_columns_and_public_upload(): void
    {
        $this->post('/manuscript', $this->payer() + [
            'mode'=>'Normal Process', 'article_type'=>'Research article', 'journal'=>'isarjahss',
            'title'=>'Test Research', 'file'=>UploadedFile::fake()->create('paper.pdf', 10, 'application/pdf'),
        ])->assertRedirect('/manuscript')->assertSessionHas('success');
        $this->assertDatabaseHas('manuscript', ['author'=>'Test Author', 'mobile'=>'1234567890',
            'type'=>'Research article', 'manuscript'=>'Test Research', 'journal'=>'Arts Journal']);
        $this->assertCount(1, File::files($this->uploadRoot.'/assets/manuscript'));
    }

    public function test_all_join_forms_save_into_the_original_request_tables(): void
    {
        foreach (['/joinAuthor'=>['authors', 'authors'], '/JoinReviewer'=>['reviewers', 'reviewer'],
            '/JoinEditor'=>['editors', 'editorsRequest']] as $route => [$table, $folder]) {
            $this->post($route, $this->payer() + [
                'expertise'=>'Science', 'publication'=>'Two papers',
                'file'=>UploadedFile::fake()->create('cv.pdf', 10, 'application/pdf'),
                'photo'=>UploadedFile::fake()->image('photo.jpg'),
            ])->assertRedirect()->assertSessionHas('success');
            $this->assertDatabaseHas($table, ['name'=>'Test Author', 'mobile'=>'1234567890',
                'specialization'=>'Science', 'publication'=>'Two papers']);
            $this->assertCount(1, File::files($this->uploadRoot.'/assets/'.$folder.'/cv'));
            $this->assertCount(1, File::files($this->uploadRoot.'/assets/'.$folder.'/photo'));
        }
    }

    public function test_database_failure_is_not_reported_as_success_and_cleans_new_upload(): void
    {
        Schema::drop('manuscript');
        $this->from('/manuscript')->post('/manuscript', $this->payer() + [
            'mode'=>'Normal Process', 'article_type'=>'Research article', 'journal'=>'isarjahss',
            'title'=>'Test Research', 'file'=>UploadedFile::fake()->create('paper.pdf', 10, 'application/pdf'),
        ])->assertRedirect('/manuscript')->assertSessionHasErrors('submission')->assertSessionMissing('success');
        $this->assertCount(0, File::files($this->uploadRoot.'/assets/manuscript'));
    }
}
