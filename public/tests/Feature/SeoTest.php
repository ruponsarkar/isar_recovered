<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeoTest extends ReadArticleTest
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url'=>'https://journals.example.org']);
        Schema::table('journals', fn (Blueprint $t) => $t->text('slug')->nullable());
        DB::table('journals')->update(['slug'=>'schema-journal']);
    }

    public function test_sitemap_has_real_canonical_urls_and_excludes_unpublished_articles(): void
    {
        $index = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->assertNotFalse(simplexml_load_string($index->getContent()));
        $index->assertSee('https://journals.example.org/sitemap-articles-1.xml', false);
        $this->get('/sitemap-journals-1.xml')->assertOk()->assertSee('/journal/schema-journal?view=archive', false);
        $this->get('/sitemap-issues-1.xml')->assertOk()->assertSee('/Articles/7', false);
        $map = $this->get('/sitemap-articles-1.xml')->assertOk()->assertSee('/viewArticle/research-paper', false);
        $this->assertNotFalse(simplexml_load_string($map->getContent()));
        $this->get('/sitemap-pages.xml')->assertOk()->assertSee('/EthicsPolicies', false)->assertDontSee('/pay/', false)->assertDontSee('/PaymentDetails', false);
        DB::table('article')->update(['status'=>0]);
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('sitemap-articles-1.xml', false);
        $this->get('/sitemap-articles-1.xml')->assertNotFound();
        $this->get('/sitemap-journals-0.xml')->assertNotFound();
    }

    public function test_scholarly_metadata_separates_authors_and_encodes_json_safely(): void
    {
        DB::table('article')->update(['aname'=>'First Author<sup>1</sup>, Second Author*<sup>2</sup>', 'keywords'=>'</script><script>alert(1)</script>']);
        $response = $this->get('/viewArticle/research-paper?tracking=test')->assertOk();
        $response->assertSee('<meta name="citation_author" content="First Author">', false)
            ->assertSee('<meta name="citation_author" content="Second Author">', false)
            ->assertSee('content="2026/09/15"', false)
            ->assertSee('https://journals.example.org/viewArticle/research-paper/fulltext.pdf', false)
            ->assertSee('"@type":"ScholarlyArticle"', false)
            ->assertDontSee('tracking=test', false);
        $this->assertSame(1, substr_count($response->getContent(), 'rel="canonical"'));
        $this->assertSame(1, substr_count($response->getContent(), 'name="description"'));
    }

    public function test_robots_references_configured_sitemap(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: https://journals.example.org/sitemap.xml', false)->assertDontSee('Disallow: /viewArticle', false);
    }
}
