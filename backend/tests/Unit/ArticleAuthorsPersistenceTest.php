<?php

use App\Http\Controllers\journalController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class ArticleAuthorsPersistenceTest extends TestCase
{
    public function test_create_and_edit_replace_author_rows_and_generate_legacy_fields(): void
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:']);
        DB::purge('sqlite');
        Schema::create('article', function (Blueprint $table) {
            $table->id();
            foreach (['name','slug','aname','abstract','designation','doi','googleScholar','page','published_date','keywords','file','i_id'] as $column) $table->text($column)->nullable();
            $table->timestamps();
        });
        $migration = require __DIR__.'/../../database/migrations/2026_09_15_000002_create_article_authors_table.php';
        $migration->up();
        $datesMigration = require __DIR__.'/../../database/migrations/2026_09_15_000003_add_review_dates_to_article_table.php';
        $datesMigration->up();
        $author = ['first_name'=>'Saikat','last_name'=>'Banerjee','designation'=>'Professor',
            'affiliation'=>'University','corresponding'=>true,'super_no'=>2];
        $payload = ['name'=>'Research', 'i_id'=>1, 'authors'=>json_encode([$author]),
            'received'=>'2026-08-01', 'revised'=>'2026-08-15', 'accepted'=>'2026-09-01'];
        $controller = new journalController;
        $response = $controller->postArticle(Request::create('/', 'POST', $payload));
        $this->assertSame(200, $response->getStatusCode());
        $id = DB::table('article')->value('id');
        $this->assertSame('Saikat Banerjee*<sup>2</sup>', DB::table('article')->value('aname'));
        $this->assertSame('<sup>2*</sup>University', DB::table('article')->value('designation'));
        $this->assertSame('Professor', DB::table('article_authors')->value('designation'));
        $this->assertSame(1, DB::table('article_authors')->where('article_id', $id)->count());
        $this->assertSame('2026-08-01', DB::table('article')->value('received'));
        $this->assertSame('2026-08-15', DB::table('article')->value('revised'));
        $this->assertSame('2026-09-01', DB::table('article')->value('accepted'));
        $author['first_name'] = 'Updated';
        $controller->postArticle(Request::create('/', 'POST', ['id'=>$id] + array_merge($payload, ['authors'=>json_encode([$author]), 'accepted'=>'2026-09-02'])));
        $this->assertSame(1, DB::table('article_authors')->count());
        $this->assertSame('Updated', DB::table('article_authors')->value('first_name'));
        $this->assertSame('Updated Banerjee*<sup>2</sup>', DB::table('article')->value('aname'));
        $this->assertSame('2026-09-02', DB::table('article')->value('accepted'));
        try {
            $controller->postArticle(Request::create('/', 'POST', ['received'=>'not-a-date']));
            $this->fail('Invalid dates must be rejected.');
        } catch (\Illuminate\Validation\ValidationException $error) {
            $this->assertArrayHasKey('received', $error->errors());
        }
        $this->assertSame(1, DB::table('article')->count());
        $root = sys_get_temp_dir().'/isar-upload-'.bin2hex(random_bytes(8));
        $app->instance('path.public', $root);
        \Illuminate\Support\Facades\File::ensureDirectoryExists($root.'/assets/articles');
        try {
            $filenames = [];
            for ($i = 0; $i < 2; $i++) {
                $upload = \Illuminate\Http\UploadedFile::fake()->createWithContent('original.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj");
                $controller->postArticle(Request::create('/', 'POST', $payload, [], ['file'=>$upload]));
                $filename = DB::table('article')->orderByDesc('id')->value('file');
                $this->assertMatchesRegularExpression('/^research-[a-f0-9-]+\\.pdf$/', $filename);
                $this->assertFileExists($root.'/assets/articles/'.$filename);
                $filenames[] = $filename;
            }
            $this->assertNotSame($filenames[0], $filenames[1]);
            $lastId = DB::table('article')->max('id');
            $controller->postArticle(Request::create('/', 'POST', ['id'=>$lastId] + $payload));
            $this->assertSame($filenames[1], DB::table('article')->where('id', $lastId)->value('file'));
        } finally {
            \Illuminate\Support\Facades\File::deleteDirectory($root);
        }

    }
}
