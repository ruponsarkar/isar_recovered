<?php

namespace Tests\Feature;

use App\Services\PublicSidebarData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JournalPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        config(['services.public_api.base_url'=>'', 'app.url'=>'http://localhost']);
        $this->mock(PublicSidebarData::class, function ($mock) {
            $mock->shouldReceive('get')->andReturn(['journals'=>[], 'editors'=>[], 'indexings'=>[], 'topEditors'=>[], 'globalIndexings'=>[]]);
        });
        Schema::create('journals', function (Blueprint $table) {
            $table->integer('j_id'); $table->string('slug'); $table->string('j_name'); $table->text('aim_and_scope')->nullable();
        });
        DB::table('journals')->insert([['j_id'=>1,'slug'=>'alpha','j_name'=>'Alpha Journal'], ['j_id'=>2,'slug'=>'beta','j_name'=>'Beta Journal']]);
        (require __DIR__.'/../../../backend/database/migrations/2026_09_16_000001_create_journal_pages_table.php')->up();
        (require __DIR__.'/../../../backend/database/migrations/2026_09_16_000003_add_link_url_to_journal_pages.php')->up();
        $this->page(1, 'current-issue', ['type'=>'system','system_key'=>'current_issue','show_in_tabs'=>true]);
        $this->page(1, 'guidelines', ['title'=>'Alpha Guidelines','content'=>'<p>Alpha rules only</p>']);
        $this->page(2, 'guidelines', ['title'=>'Beta Guidelines','content'=>'<p>Beta rules only</p>']);
    }

    public function test_journal_sidebar_only_shows_active_impact_factors(): void
    {
        Schema::create('impact_factors', function (Blueprint $table) {
            $table->increments('id'); $table->integer('j_id'); $table->string('impact');
            $table->string('link')->nullable(); $table->integer('isActive')->nullable();
        });
        DB::table('impact_factors')->insert([
            ['j_id'=>1, 'impact'=>'Active factor', 'isActive'=>1],
            ['j_id'=>1, 'impact'=>'Deleted factor', 'isActive'=>0],
            ['j_id'=>1, 'impact'=>'Unset factor', 'isActive'=>null],
            ['j_id'=>2, 'impact'=>'Other journal factor', 'isActive'=>1],
        ]);
        $this->get('/journal/alpha')->assertOk()->assertSee('Active factor')
            ->assertDontSee('Deleted factor')->assertDontSee('Unset factor')->assertDontSee('Other journal factor');
        DB::table('impact_factors')->where('j_id', 1)->update(['isActive'=>0]);
        $this->get('/journal/alpha')->assertOk()->assertDontSee('Impact Factor')->assertDontSee('Active factor');
    }

    private function page(int $journal, string $slug, array $changes = []): int
    {
        return DB::table('journal_pages')->insertGetId(array_merge(['journal_id'=>$journal,'slug'=>$slug,'title'=>$slug,'type'=>'content','content'=>'<p>Page body</p>','sort_order'=>10,'is_published'=>true,'show_in_nav'=>true,'show_in_tabs'=>false], $changes));
    }

    public function test_journal_pages_are_scoped_and_home_nav_is_unchanged(): void
    {
        $this->get('/journal/alpha/pages/guidelines')->assertOk()->assertSee('Alpha rules only')->assertDontSee('Beta rules only')->assertSee('/journal/alpha/pages/guidelines', false)->assertSee('aria-label="Journal navigation"', false)->assertSee('Alpha Guidelines | Alpha Journal');
        $this->get('/journal/beta/pages/guidelines')->assertOk()->assertSee('Beta rules only')->assertDontSee('Alpha rules only');
        $this->get('/')->assertOk()->assertSee('href="/author-instruction"', false)->assertDontSee('aria-label="Journal navigation"', false);
        $this->get('/journal/missing')->assertNotFound();
        $this->get('/journal/alpha/pages/missing')->assertNotFound();
    }

    public function test_link_destinations_are_used_by_navbar_dropdowns_and_tabs(): void
    {
        $this->page(1, 'search', ['type'=>'link','link_url'=>'https://www.google.com','show_in_tabs'=>true,'sort_order'=>0]);
        $group = $this->page(1, 'resources', ['type'=>'group']);
        $this->page(1, 'payment-link', ['type'=>'link','link_url'=>'/payment','parent_id'=>$group,'show_in_tabs'=>true]);
        $response = $this->get('/journal/alpha')->assertOk()->assertSee('No current issue');
        $html = $response->getContent();
        $this->assertSame(2, substr_count($html, 'href="https://www.google.com"'));
        $this->assertSame(2, substr_count($html, 'href="/payment"'));
        $this->get('/journal/alpha/pages/search')->assertRedirect('https://www.google.com');
        $this->get('/journal/alpha/pages/payment-link')->assertRedirect('/payment');
        $this->get('/journal/beta/pages/search')->assertNotFound();
        DB::table('journal_pages')->where('slug','search')->update(['is_published'=>false]);
        $this->get('/journal/alpha/pages/search')->assertNotFound();
        DB::table('journal_pages')->where('id',$group)->update(['is_published'=>false]);
        $this->get('/journal/alpha/pages/payment-link')->assertNotFound();
        $this->page(1, 'unsafe-link', ['type'=>'link','link_url'=>'javascript:alert(1)']);
        $this->get('/journal/alpha')->assertOk()->assertDontSee('javascript:', false);
        $this->get('/journal/alpha/pages/unsafe-link')->assertNotFound();
        $this->get('/sitemap-journal-pages-1.xml')->assertOk()->assertDontSee('/pages/payment-link', false)->assertDontSee('/pages/unsafe-link', false);
    }

    public function test_drafts_parent_visibility_and_hidden_published_pages(): void
    {
        $group = $this->page(1, 'authors', ['type'=>'group','title'=>'Authors menu']);
        $this->page(1, 'child', ['parent_id'=>$group, 'title'=>'Nested guidelines']);
        $this->page(1, 'draft', ['is_published'=>false, 'content'=>'DRAFT SECRET']);
        $this->page(1, 'hidden', ['show_in_nav'=>false, 'content'=>'Direct link only']);
        $this->get('/journal/alpha')->assertOk()->assertSee('Authors menu')->assertSee('Nested guidelines')->assertDontSee('DRAFT SECRET')->assertDontSee('/pages/hidden');
        $this->get('/journal/alpha/pages/hidden')->assertOk()->assertSee('Direct link only');
        $this->get('/journal/alpha/pages/draft')->assertNotFound();
        $this->get('/journal/alpha/pages/authors')->assertNotFound();
        DB::table('journal_pages')->where('id',$group)->update(['is_published'=>false]);
        $this->get('/journal/alpha')->assertOk()->assertDontSee('Nested guidelines');
        $this->get('/journal/alpha/pages/child')->assertNotFound();
        DB::table('journal_pages')->where('journal_id',1)->update(['is_published'=>false]);
        $this->get('/journal/alpha')->assertOk()->assertSee('No pages are published');
        $this->get('/journal/alpha?view=current_issue')->assertNotFound();
    }

    public function test_system_sections_legacy_urls_and_html_safety(): void
    {
        $this->page(1, 'about', ['content'=>'<p>Unique aims</p>','show_in_tabs'=>true]);
        $this->page(1, 'archive', ['type'=>'system','system_key'=>'archive','show_in_tabs'=>true]);
        $this->page(1, 'editorial-board', ['type'=>'system','system_key'=>'editorial_board']);
        $this->get('/journal/alpha?view=about')->assertOk()->assertSee('Unique aims');
        $this->get('/journal/alpha?view=archive')->assertOk()->assertSee('No archive issues');
        $this->get('/journal/alpha/pages/editorial-board')->assertOk()->assertSee('Chief Editor');
        $this->get('/journal/alpha/pages/current-issue')->assertOk()->assertSee('No current issue')->assertSee('href="http://localhost/journal/alpha"', false);
        $this->page(1, 'unsafe', ['content'=>'<h2>Safe title</h2><script>alert(1)</script><a href="javascript:evil()" onclick="evil()">Link</a>']);
        $this->get('/journal/alpha/pages/unsafe')->assertOk()->assertSee('<h2>Safe title</h2>', false)->assertDontSee('<script>alert(1)', false)->assertDontSee('href="javascript:', false)->assertDontSee('onclick=', false);
    }

    public function test_issue_archive_and_editorial_sections_keep_journal_records_separate(): void
    {
        Schema::create('volume', function (Blueprint $table) { $table->integer('id'); $table->integer('j_id'); $table->string('name'); });
        Schema::create('issues', function (Blueprint $table) { $table->integer('id'); $table->integer('v_id'); $table->string('name'); });
        Schema::create('editors_data', function (Blueprint $table) { $table->integer('id'); $table->integer('j_id'); $table->string('name'); $table->string('type'); $table->integer('active'); });
        DB::table('volume')->insert([['id'=>11,'j_id'=>1,'name'=>'Alpha Volume'],['id'=>22,'j_id'=>2,'name'=>'Beta Volume']]);
        DB::table('issues')->insert([['id'=>101,'v_id'=>11,'name'=>'Alpha Issue'],['id'=>202,'v_id'=>22,'name'=>'Beta Issue']]);
        DB::table('editors_data')->insert([['id'=>1,'j_id'=>1,'name'=>'Alpha Editor','type'=>'chief','active'=>1],['id'=>2,'j_id'=>2,'name'=>'Beta Editor','type'=>'chief','active'=>1]]);
        $this->page(1, 'archive', ['type'=>'system','system_key'=>'archive']);
        $this->page(1, 'editors', ['type'=>'system','system_key'=>'editorial_board']);
        $this->get('/journal/alpha')->assertOk()->assertSee('Alpha Issue')->assertSee('/Articles/101', false)->assertDontSee('Beta Issue');
        $this->get('/journal/alpha/pages/archive')->assertOk()->assertSee('Alpha Volume')->assertDontSee('Beta Volume');
        $this->get('/Articles/101')->assertOk()->assertSee('All Articles')->assertSee('Alpha Volume')->assertSee('aria-label="Journal navigation"', false)->assertSee('/journal/alpha/pages/guidelines', false)->assertDontSee('Beta Guidelines');
        $this->get('/Articles/202')->assertOk()->assertSee('Beta Volume')->assertSee('/journal/beta/pages/guidelines', false)->assertDontSee('Alpha Guidelines');
        $this->get('/journal/alpha/pages/editors')->assertOk()->assertSee('Alpha Editor')->assertDontSee('Beta Editor');
    }

    public function test_remote_pages_do_not_fall_back_to_stale_local_published_content(): void
    {
        config(['services.public_api.base_url'=>'https://api.example.test/api']);
        $journal = ['j_id'=>1,'slug'=>'alpha','j_name'=>'Alpha Journal'];
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake(['https://api.example.test/api/journals/alpha/pages'=>Http::response(['pages'=>[]])]);
        $this->assertSame([], app(\App\Services\JournalPages::class)->published($journal, true));
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake(['https://api.example.test/api/journals/alpha/pages'=>Http::response([], 500)]);
        try {
            app(\App\Services\JournalPages::class)->published($journal, true);
            $this->fail('Unavailable remote content must not fall back to stale rows.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $error) {
            $this->assertSame(503, $error->getStatusCode());
        }
    }

    public function test_public_route_renders_configured_remote_journal_pages(): void
    {
        config(['services.public_api.base_url'=>'https://api.example.test/api']);
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake([
            'https://api.example.test/api/journalDetails/alpha'=>Http::response(['journal'=>['j_id'=>1,'slug'=>'alpha','j_name'=>'Remote Alpha']]),
            'https://api.example.test/api/journals/alpha/pages'=>Http::response(['pages'=>[['id'=>200,'journal_id'=>1,'slug'=>'remote','title'=>'Remote policy','type'=>'content','parent_id'=>null,'content'=>'<p>Remote content only</p>','sort_order'=>0,'show_in_nav'=>true,'show_in_tabs'=>false,'is_published'=>true]]]),
            '*'=>Http::response([]),
        ]);
        $this->get('/journal/alpha/pages/remote')->assertOk()->assertSee('Remote content only')->assertDontSee('Alpha rules only');
        $this->get('/journal/alpha/pages/guidelines')->assertNotFound();
    }

    public function test_remote_issue_uses_its_journal_navigation(): void
    {
        config(['services.public_api.base_url'=>'https://api.example.test/api']);
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake([
            'https://api.example.test/api/getArticle/303'=>Http::response([
                'getIssue'=>['id'=>303,'v_id'=>33,'name'=>'Remote Issue'],
                'getVol'=>['id'=>33,'j_id'=>1,'name'=>'Remote Volume'],
                'getJournal'=>['j_id'=>1,'slug'=>'alpha','j_name'=>'Remote Alpha'],
                'article'=>[],
            ]),
            'https://api.example.test/api/journals/alpha/pages'=>Http::response(['pages'=>[['id'=>200,'journal_id'=>1,'slug'=>'remote','title'=>'Remote policy','type'=>'content','parent_id'=>null,'sort_order'=>0,'show_in_nav'=>true,'is_published'=>true]]]),
            '*'=>Http::response([]),
        ]);
        $this->get('/Articles/303')->assertOk()->assertSee('All Articles')->assertSee('Remote Volume')->assertSee('Remote Alpha')->assertSee('aria-label="Journal navigation"', false)->assertSee('/journal/alpha/pages/remote', false)->assertDontSee('Alpha Guidelines');
    }

    public function test_sitemap_contains_only_published_journal_content(): void
    {
        $this->page(1, 'draft', ['is_published'=>false]);
        $group = $this->page(1, 'private-group', ['type'=>'group','is_published'=>false]);
        $this->page(1, 'private-child', ['parent_id'=>$group]);
        $this->get('/sitemap-journal-pages-1.xml')->assertOk()->assertSee('/journal/alpha/pages/guidelines', false)->assertSee('/journal/beta/pages/guidelines', false)->assertDontSee('/pages/draft', false)->assertDontSee('/pages/private-child', false);
        $this->get('/sitemap-journals-1.xml')->assertOk()->assertDontSee('?view=archive', false)->assertDontSee('?view=about', false);
    }
}
