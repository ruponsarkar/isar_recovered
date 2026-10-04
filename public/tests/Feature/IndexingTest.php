<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PublicSidebarData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class IndexingTest extends TestCase
{
    public function test_home_filters_indexing_and_renders_uploaded_images(): void
    {
        config(['services.public_api.base_url'=>'']);
        Schema::create('indexing', function (Blueprint $t) {
            $t->integer('id'); $t->integer('j_id'); $t->integer('isShow'); $t->string('img'); $t->string('link');
        });
        DB::table('indexing')->insert([
            ['id'=>1,'j_id'=>1,'isShow'=>1,'img'=>'visible.png','link'=>'https://example.com/index'],
            ['id'=>2,'j_id'=>2,'isShow'=>0,'img'=>'hidden.png','link'=>'https://example.com/hidden'],
        ]);
        $data = app(PublicSidebarData::class)->get();
        $this->assertSame([1], array_column($data['indexings'], 'id'));
        $this->get('/')->assertOk()->assertSee('indexing/visible.png', false)->assertSee('https://example.com/index', false)->assertDontSee('hidden.png');
        DB::table('indexing')->update(['isShow'=>0]);
        $this->assertSame([], app(PublicSidebarData::class)->get()['indexings']);
    }

    public function test_api_home_indexing_is_filtered(): void
    {
        config(['services.public_api.base_url'=>'https://api.example.test']);
        Http::fake(['*/getJournals'=>Http::response(['indexings_unique'=>[
            ['id'=>1,'isShow'=>1,'img'=>'visible.png'], ['id'=>2,'isShow'=>0,'img'=>'hidden.png'],
        ]])]);
        $this->assertSame([1], array_column(app(PublicSidebarData::class)->get()['indexings'], 'id'));
    }
}
