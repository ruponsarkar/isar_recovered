<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JournalAssetTest extends \Illuminate\Foundation\Testing\TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    public function test_download_uses_title_and_rejects_missing_or_unsafe_files(): void
    {
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:']);
        DB::purge('sqlite');
        Schema::create('article', function (Blueprint $t) {
            $t->increments('id'); $t->text('name'); $t->text('file'); $t->integer('status'); $t->integer('count')->nullable();
        });
        DB::table('article')->insert(['id'=>1, 'name'=>'Research: A / B?', 'file'=>'old-upload.pdf', 'status'=>1]);
        $root = sys_get_temp_dir().'/isar-download-'.bin2hex(random_bytes(8));
        $this->app->instance('path.public', $root);
        \Illuminate\Support\Facades\File::ensureDirectoryExists($root.'/assets/articles');
        try {
            $this->get('/api/downloadArticle/1')->assertNotFound();
            \Illuminate\Support\Facades\File::put($root.'/assets/articles/old-upload.pdf', '%PDF-1.4 test');
            $this->get('/api/downloadArticle/1')->assertOk()->assertDownload('research-a-b.pdf');
            $this->assertSame(1, DB::table('article')->value('count'));
            $this->get('/api/articles/1/research-a-b.pdf')->assertOk()->assertHeader('Content-Disposition', 'inline; filename=research-a-b.pdf');
            $this->get('/api/downloadArticle/1?inline=1')->assertOk()->assertHeader('Content-Type', 'application/pdf')->assertHeader('Content-Disposition', 'inline; filename=research-a-b.pdf');
            DB::table('article')->update(['file'=>'../old-upload.pdf']);
            $this->get('/api/downloadArticle/1')->assertNotFound();
            DB::table('article')->update(['file'=>'old-upload.pdf', 'status'=>0]);
            $this->get('/api/downloadArticle/1')->assertNotFound();
        } finally {
            \Illuminate\Support\Facades\File::deleteDirectory($root);
        }
    }

    public function test_deletion_requires_authentication_and_matching_journal(): void
    {
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:', 'cache.default'=>'array']);
        DB::purge('sqlite');
        foreach (['indexing', 'certificate', 'editors_data'] as $table) {
            Schema::create($table, function (Blueprint $t) { $t->increments('id'); $t->integer('j_id'); });
            DB::table($table)->insert([['id'=>1,'j_id'=>10], ['id'=>2,'j_id'=>20]]);
            $this->postJson('/api/delete'.($table === 'editors_data' ? 'Editor' : ucfirst($table)), ['id'=>1,'j_id'=>10])->assertUnauthorized();
        }
        $user = new User;
        $user->id = 1;
        $this->actingAs($user, 'api');
        foreach (['indexing', 'certificate', 'editors_data'] as $table) {
            $url = '/api/delete'.($table === 'editors_data' ? 'Editor' : ucfirst($table));
            $this->postJson($url, ['id'=>1,'j_id'=>20])->assertNotFound();
            $this->assertSame(2, DB::table($table)->count());
            $this->postJson($url, ['id'=>1])->assertUnprocessable();
            $this->postJson($url, ['id'=>1,'j_id'=>10])->assertOk();
            $this->assertSame([2], DB::table($table)->pluck('id')->all());
            $this->postJson($url, ['id'=>1,'j_id'=>10])->assertNotFound();
        }
    }
}
