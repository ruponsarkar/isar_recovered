<?php

namespace Tests\Feature;

use App\Models\journal;
use App\Models\JournalPage;
use App\Models\User;
use App\Services\JournalPageDefaults;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JournalPageTest extends \Illuminate\Foundation\Testing\TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:', 'cache.default'=>'array', 'app.key'=>'base64:8SZEVjIYvNGYQJZD+jBm58dYPhX0QphVdC8PQtzZx70=']);
        DB::purge('sqlite');
        Schema::create('journals', function (Blueprint $table) {
            $table->increments('j_id');
            $table->string('slug'); $table->string('j_name'); $table->text('aim_and_scope')->nullable(); $table->timestamps();
        });
        DB::table('journals')->insert([
            ['j_id'=>1,'slug'=>'alpha','j_name'=>'Alpha Journal','aim_and_scope'=>'<p>Alpha aims</p>'],
            ['j_id'=>2,'slug'=>'beta','j_name'=>'Beta Journal','aim_and_scope'=>'<p>Beta aims</p>'],
        ]);
        (require __DIR__.'/../../database/migrations/2026_09_16_000001_create_journal_pages_table.php')->up();
        (require __DIR__.'/../../database/migrations/2026_09_16_000003_add_link_url_to_journal_pages.php')->up();
        (require __DIR__.'/../../database/migrations/2026_09_16_000002_seed_default_journal_pages.php')->up();
    }

    private function admin(): void
    {
        $user = new User;
        $user->id = 1;
        $this->actingAs($user, 'api');
    }

    public function test_link_pages_accept_web_urls_and_internal_paths_and_clear_old_destinations(): void
    {
        $this->admin();
        $url = 'https://www.google.com/search?q=journal';
        $response = $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['type'=>'link','link_url'=>$url,'show_in_tabs'=>true]))->assertCreated()->assertJsonPath('page.link_url', $url)->assertJsonPath('page.content', null);
        $id = $response->json('page.id');
        $this->getJson('/api/journals/alpha/pages/author-faq')->assertOk()->assertJsonPath('page.link_url', $url);
        $this->putJson('/api/admin/journals/alpha/pages/'.$id, $this->payload(['type'=>'link','link_url'=>'/payment?journal=alpha#details']))->assertOk()->assertJsonPath('page.link_url', '/payment?journal=alpha#details');
        $this->putJson('/api/admin/journals/alpha/pages/'.$id, $this->payload())->assertOk()->assertJsonPath('page.link_url', null);
        foreach (['', ['https://example.com'], 'https:google.com', 'javascript:alert(1)', 'data:text/html,test', '//example.com', '/\\example.com', '/%2fexample.com', '/%0d%0aLocation:bad', 'https://example.com/a b'] as $invalid) {
            $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['slug'=>'bad-link','type'=>'link','link_url'=>$invalid]))->assertUnprocessable()->assertJsonValidationErrors('link_url');
        }
    }

    private function payload(array $changes = []): array
    {
        return array_merge(['title'=>'Author FAQ','slug'=>'author-faq','type'=>'content','system_key'=>null,'content'=>'<p>Alpha only</p>','parent_id'=>null,'sort_order'=>15,'is_published'=>true,'show_in_nav'=>true,'show_in_tabs'=>false], $changes);
    }

    public function test_admin_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/journals/alpha/pages')->assertUnauthorized();
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload())->assertUnauthorized();
        $this->putJson('/api/admin/journals/alpha/pages/1', $this->payload())->assertUnauthorized();
        $this->deleteJson('/api/admin/journals/alpha/pages/1')->assertUnauthorized();
    }

    public function test_defaults_are_independent_idempotent_and_new_journals_get_them(): void
    {
        $this->assertSame('<p>Alpha aims</p>', JournalPage::where('journal_id',1)->where('slug','about')->value('content'));
        $this->assertSame('<p>Beta aims</p>', JournalPage::where('journal_id',2)->where('slug','about')->value('content'));
        $count = JournalPage::count();
        app(JournalPageDefaults::class)->seed(1);
        $this->assertSame($count, JournalPage::count());
        $new = new journal;
        $new->slug = 'gamma'; $new->j_name = 'Gamma'; $new->aim_and_scope = '<p>Gamma aims</p>'; $new->save();
        $this->assertSame('<p>Gamma aims</p>', JournalPage::where('journal_id',$new->j_id)->where('slug','about')->value('content'));
        $this->admin();
        $id = JournalPage::where('journal_id',1)->where('slug','about')->value('id');
        $this->deleteJson('/api/admin/journals/alpha/pages/'.$id)->assertNoContent();
        $this->getJson('/api/admin/journals/alpha/pages')->assertOk();
        $this->getJson('/api/journals/alpha/pages/about')->assertNotFound();
        $this->getJson('/api/journals/beta/pages/about')->assertOk();
    }

    public function test_crud_scopes_pages_and_sanitizes_published_html(): void
    {
        $this->admin();
        $response = $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['content'=>'<h2>FAQ</h2><script>alert(1)</script><a href="javascript:alert(1)" onclick="evil()">Link</a><img src="/assets/logo.png" onerror="evil()">']))->assertCreated();
        $id = $response->json('page.id');
        $this->assertStringNotContainsString('script', $response->json('page.content'));
        $this->assertStringNotContainsString('onerror', $response->json('page.content'));
        $this->assertStringContainsString('<h2>FAQ</h2>', $response->json('page.content'));
        $this->getJson('/api/journals/alpha/pages/author-faq')->assertOk();
        $this->getJson('/api/journals/beta/pages/author-faq')->assertNotFound();
        $this->putJson('/api/admin/journals/beta/pages/'.$id, $this->payload())->assertNotFound();
        $this->deleteJson('/api/admin/journals/beta/pages/'.$id)->assertNotFound();
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload())->assertUnprocessable()->assertJsonValidationErrors('slug');
        $this->postJson('/api/admin/journals/beta/pages', $this->payload())->assertCreated();
        $this->putJson('/api/admin/journals/alpha/pages/'.$id, $this->payload(['is_published'=>false]))->assertOk();
        $this->getJson('/api/journals/alpha/pages/author-faq')->assertNotFound();
        $this->deleteJson('/api/admin/journals/alpha/pages/'.$id)->assertNoContent();
    }

    public function test_dropdown_boundaries_drafts_and_ordering(): void
    {
        $this->admin();
        $group = JournalPage::where('journal_id',1)->where('slug','for-authors')->first();
        $foreign = JournalPage::where('journal_id',2)->where('slug','for-authors')->first();
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['parent_id'=>$foreign->id]))->assertUnprocessable();
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['parent_id'=>$group->id]))->assertCreated();
        $this->deleteJson('/api/admin/journals/alpha/pages/'.$group->id)->assertUnprocessable();
        $this->putJson('/api/admin/journals/alpha/pages/'.$group->id, $this->payload(['slug'=>'for-authors']))->assertUnprocessable();
        $this->putJson('/api/admin/journals/alpha/pages/'.$group->id, $this->payload(['slug'=>'for-authors','type'=>'group','is_published'=>false]))->assertOk();
        $this->getJson('/api/journals/alpha/pages/author-faq')->assertNotFound();
        $this->getJson('/api/journals/alpha/pages')->assertOk()->assertJsonMissing(['slug'=>'for-authors'])->assertJsonMissing(['slug'=>'author-faq']);
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['slug'=>'first','sort_order'=>0]))->assertCreated();
        $this->assertSame('first', $this->getJson('/api/journals/alpha/pages')->json('pages.0.slug'));
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['slug'=>'nested','type'=>'group','parent_id'=>$group->id]))->assertUnprocessable();
        $this->postJson('/api/admin/journals/alpha/pages', $this->payload(['slug'=>'invalid','type'=>'system']))->assertUnprocessable();
    }
}
