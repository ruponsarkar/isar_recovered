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

    public function test_manuscript_is_saved_with_legacy_columns_and_public_upload(): void
    {
        $this->post('/manuscript', $this->payer() + [
            'mode'=>'Normal Process', 'article_type'=>'Research article', 'journal'=>'isarjahss',
            'title'=>'Test Research', 'file'=>UploadedFile::fake()->create('paper.pdf', 10, 'application/pdf'),
        ])->assertRedirect('/manuscript')->assertSessionHas('success');
        $this->assertDatabaseHas('manuscript', ['author'=>'Test Author', 'mobile'=>'1234567890',
            'type'=>'Research article', 'manuscript'=>'Test Research']);
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
