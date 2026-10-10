<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactDetailsTest extends \Illuminate\Foundation\Testing\TestCase
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
        config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>':memory:', 'cache.default'=>'array']);
        DB::purge('sqlite');
        Schema::create('journals', function (Blueprint $t) { $t->integer('j_id'); $t->string('slug'); $t->string('j_name'); });
        DB::table('journals')->insert([['j_id'=>1,'slug'=>'alpha','j_name'=>'Alpha'],['j_id'=>2,'slug'=>'beta','j_name'=>'Beta']]);
        (require __DIR__.'/../../database/migrations/2026_10_01_000001_create_contact_details_table.php')->up();
    }
    public function test_authentication_validation_and_fallback_lifecycle(): void
    {
        $url = '/api/admin/contact-details/';
        $this->putJson($url.'default', [])->assertUnauthorized();
        $this->getJson($url.'default')->assertUnauthorized();
        $this->deleteJson($url.'1')->assertUnauthorized();
        $user = new User; $user->id = 1;
        $this->actingAs($user, 'api');
        $default = ['address'=>'Publisher office','email'=>'office@example.com','phone'=>'+91 1234567890'];
        $this->putJson($url.'default', $default)->assertOk()->assertJsonPath('contact.address', 'Publisher office');
        $this->putJson($url.'1', ['address'=>'Journal office','email'=>'','phone'=>''])->assertOk();
        $this->getJson('/api/contact-details/1')->assertOk()->assertJsonPath('contact.address', 'Journal office')->assertJsonPath('contact.email', 'office@example.com');
        $this->getJson('/api/contact-details/2')->assertOk()->assertJsonPath('contact.address', 'Publisher office');
        $this->putJson($url.'default', ['address'=>'','email'=>'bad','phone'=>'javascript:bad'])->assertUnprocessable();
        $this->putJson($url.'999', $default)->assertNotFound();
        $this->deleteJson($url.'default')->assertUnprocessable();
        $this->deleteJson($url.'1')->assertOk();
        $this->getJson('/api/contact-details/1')->assertOk()->assertJsonPath('contact.address', 'Publisher office');
    }
}
