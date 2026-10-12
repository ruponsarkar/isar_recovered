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

    public function test_article_shows_its_journal_navigation(): void
    {
        Schema::table('journals', function (Blueprint $table) { $table->string('slug')->nullable(); });
        DB::table('journals')->where('j_id', 9)->update(['slug'=>'schema-journal']);
        (require __DIR__.'/../../../backend/database/migrations/2026_09_16_000001_create_journal_pages_table.php')->up();
        foreach ([9=>'Journal Guidelines', 10=>'Other Journal Guidelines'] as $journalId=>$title) {
            DB::table('journal_pages')->insert(['journal_id'=>$journalId, 'slug'=>'guidelines', 'title'=>$title, 'type'=>'content', 'sort_order'=>0, 'is_published'=>true, 'show_in_nav'=>true, 'show_in_tabs'=>false]);
        }
        $this->get('/viewArticle/research-paper')->assertOk()
            ->assertSee('aria-label="Journal navigation"', false)
            ->assertSee('/journal/schema-journal/pages/guidelines', false)
            ->assertSee('Journal Guidelines')->assertDontSee('Other Journal Guidelines')
            ->assertDontSee('id="mainNav"', false);
    }

    public function test_missing_and_unpublished_articles_are_404(): void
    {
        $this->get('/viewArticle/missing')->assertNotFound();
        DB::table('article')->where('id',6)->update(['status'=>0]);
        $this->get('/viewArticle/research-paper')->assertNotFound();
        $this->get('/download/6')->assertNotFound();
    }

    public function test_shared_theme_is_loaded_after_page_styles_on_home_and_article_pages(): void
    {
        $this->mock(PublicSidebarData::class, function ($mock) {
            $mock->shouldReceive('get')->andReturn(['journals'=>[], 'editors'=>[], 'indexings'=>[]]);
        });
        foreach (['/' => 'home.css', '/viewArticle/research-paper' => 'read-article.css'] as $url => $pageStyle) {
            $html = $this->get($url)->assertOk()->assertSee('class="public-site ', false)->getContent();
            $this->assertSame(1, substr_count($html, '/theme.css"'));
            $this->assertNotFalse(strpos($html, '/'.$pageStyle.'"'));
            $this->assertGreaterThan(strpos($html, '/'.$pageStyle.'"'), strpos($html, '/theme.css"'));
        }
    }

    public function test_home_and_article_list_link_to_local_detail_with_affiliations_on_next_line(): void
    {
        $this->mock(PublicSidebarData::class, function ($mock) { $mock->shouldReceive('get')->andReturn(['journals'=>[],'editors'=>[],'indexings'=>[]]); });
        $this->get('/')->assertOk()->assertSee('article-meta flex-column align-items-start', false)->assertSee('article-affiliations w-100', false)->assertSee('/viewArticle/research-paper', false)->assertSee('/viewArticle/research-paper/real-article-title.pdf', false)->assertDontSee('/assets/articles/test-paper.pdf', false)->assertDontSee('/Articles/research-paper', false);
        $this->get('/Articles/7')->assertOk()->assertSee('/viewArticle/research-paper', false)->assertSee('/viewArticle/research-paper/real-article-title.pdf', false)->assertDontSee('/assets/articles/test-paper.pdf', false)->assertDontSee('article.isarpublisher.com', false);
    }

    public function test_backend_assets_use_configured_url_for_images_and_pdf_redirects(): void
    {
        config(['app.backend_asset_url' => 'https://cdn.example.org/backend/assets/']);
        $this->assertSame('https://cdn.example.org/backend/assets/journal/cover.jpg', backend_asset_url('/journal/cover.jpg'));
        $this->assertStringContainsString('https://cdn.example.org/backend/assets/mainlogo.png', view('partials.nav')->render());
        $this->get('/download/6')->assertRedirect('https://cdn.example.org/backend/assets/articles/test-paper.pdf');
    }

    public function test_local_download_increments_only_when_valid_file_exists(): void
    {
        config(['app.backend_asset_url' => null]);
        $root = sys_get_temp_dir().'/isar-article-'.bin2hex(random_bytes(8));
        $this->app->instance('path.public',$root);
        try {
            $this->get('/download/6')->assertNotFound();
            $this->assertSame(0, DB::table('article')->where('id',6)->value('count'));
            File::ensureDirectoryExists($root.'/assets/articles');
            File::put($root.'/assets/articles/test-paper.pdf','%PDF-1.4 test');
            $this->get('/download/6')->assertOk()->assertDownload('real-article-title.pdf');
            $this->assertSame(1, DB::table('article')->where('id',6)->value('count'));
            $this->get('/articles/6/real-article-title.pdf')->assertOk()->assertHeader('Content-Disposition', 'inline; filename=real-article-title.pdf');
            $this->get('/viewArticle/research-paper/real-article-title.pdf')->assertOk()->assertHeader('Content-Disposition', 'inline; filename=real-article-title.pdf');
            $this->get('/viewArticle/research-paper/fulltext.pdf')->assertOk()->assertHeader('Link', '<'.\App\Services\Seo::url('/viewArticle/research-paper').'>; rel="canonical"');
            DB::table('article')->where('id',6)->update(['file'=>'../test-paper.pdf']);
            $this->get('/download/6')->assertNotFound();
            $this->assertSame(2, DB::table('article')->where('id',6)->value('count'));
        } finally { File::deleteDirectory($root); }
    }
}
