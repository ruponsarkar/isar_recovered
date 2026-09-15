<?php

namespace Tests\Feature;

use App\Services\PublicSidebarData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReadArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        Schema::create('article', function (Blueprint $t) {
            $t->increments('id'); $t->integer('i_id'); $t->integer('status'); $t->integer('count')->nullable();
            foreach (['slug','name','aname','designation','abstract','keywords','published_date','googleScholar','doi','file'] as $column) $t->text($column)->nullable();
        });
        Schema::create('issues', function (Blueprint $t) { $t->integer('id'); $t->integer('v_id'); $t->text('name'); });
        Schema::create('volume', function (Blueprint $t) { $t->integer('id'); $t->integer('j_id'); $t->text('name'); });
        Schema::create('journals', function (Blueprint $t) { $t->integer('j_id'); foreach(['j_name','issn','frequency','language','photo'] as $column) $t->text($column)->nullable(); });
        DB::table('journals')->insert(['j_id'=>9,'j_name'=>'Schema Journal','issn'=>'1234-5678','frequency'=>'Monthly','language'=>'English']);
        DB::table('volume')->insert(['id'=>8,'j_id'=>9,'name'=>'Volume 8']);
        DB::table('issues')->insert(['id'=>7,'v_id'=>8,'name'=>'Issue 7']);
        DB::table('article')->insert(['id'=>6,'i_id'=>7,'status'=>1,'count'=>0,'slug'=>'research-paper','name'=>'Real article title','aname'=>'First Author<sup>1*</sup>','designation'=>'<sup>1*</sup>Test University','abstract'=>'<p>Safe "quoted" abstract</p>','keywords'=>'Research','published_date'=>'2026-09-15','googleScholar'=>'javascript:alert(1)','file'=>'test-paper.pdf']);
    }

    public function test_detail_uses_article_title_and_journal_schema_and_safe_metadata(): void
    {
        $this->get('/viewArticle/research-paper')->assertOk()->assertSee('Real article title')->assertSee('Schema Journal')->assertSee('1234-5678')->assertSee('First Author<sup>1*</sup>', false)->assertSee('read-article-affiliations')->assertSee('<sup>1*</sup>Test University', false)->assertSee('Safe &quot;quoted&quot; abstract', false)->assertDontSee('javascript:alert(1)', false);
    }

    public function test_missing_and_unpublished_articles_are_404(): void
    {
        $this->get('/viewArticle/missing')->assertNotFound();
        DB::table('article')->where('id',6)->update(['status'=>0]);
        $this->get('/viewArticle/research-paper')->assertNotFound();
        $this->get('/download/6')->assertNotFound();
    }

    public function test_home_and_article_list_link_to_local_detail_with_affiliations_on_next_line(): void
    {
        $this->mock(PublicSidebarData::class, function ($mock) { $mock->shouldReceive('get')->andReturn(['journals'=>[],'editors'=>[],'indexings'=>[]]); });
        $this->get('/')->assertOk()->assertSee('article-meta flex-column align-items-start', false)->assertSee('article-affiliations w-100', false)->assertSee('/viewArticle/research-paper', false)->assertSee('/download/6', false)->assertDontSee('/assets/articles/test-paper.pdf', false)->assertDontSee('/Articles/research-paper', false);
        $this->get('/Articles/7')->assertOk()->assertSee('/viewArticle/research-paper', false)->assertSee('/download/6', false)->assertDontSee('/assets/articles/test-paper.pdf', false)->assertDontSee('article.isarpublisher.com', false);
    }

    public function test_local_download_increments_only_when_valid_file_exists(): void
    {
        $root = sys_get_temp_dir().'/isar-article-'.bin2hex(random_bytes(8));
        $this->app->instance('path.public',$root);
        try {
            $this->get('/download/6')->assertNotFound();
            $this->assertSame(0, DB::table('article')->where('id',6)->value('count'));
            File::ensureDirectoryExists($root.'/assets/articles');
            File::put($root.'/assets/articles/test-paper.pdf','%PDF-1.4 test');
            $this->get('/download/6')->assertOk()->assertDownload('test-paper.pdf');
            $this->assertSame(1, DB::table('article')->where('id',6)->value('count'));
            DB::table('article')->where('id',6)->update(['file'=>'../test-paper.pdf']);
            $this->get('/download/6')->assertNotFound();
            $this->assertSame(1, DB::table('article')->where('id',6)->value('count'));
        } finally { File::deleteDirectory($root); }
    }
}
