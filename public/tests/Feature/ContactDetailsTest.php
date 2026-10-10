<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ContactDetails;
use App\Services\PublicSidebarData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ContactDetailsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.public_api.base_url'=>'']);
        Http::fake();
        $this->mock(PublicSidebarData::class, function ($mock) {
            $mock->shouldReceive('get')->andReturn(['journals'=>[], 'editors'=>[], 'indexings'=>[], 'topEditors'=>[], 'globalIndexings'=>[]]);
        });
        (require __DIR__.'/../../../backend/database/migrations/2026_10_01_000001_create_contact_details_table.php')->up();
        Schema::create('journals', function (Blueprint $t) { $t->integer('j_id'); $t->string('slug'); $t->string('j_name'); });
        DB::table('journals')->insert([['j_id'=>1,'slug'=>'alpha','j_name'=>'Alpha'], ['j_id'=>2,'slug'=>'beta','j_name'=>'Beta']]);
        DB::table('contact_details')->insert(['journal_id'=>0,'address'=>'Default office','email'=>'office@example.com','phone'=>'+91 1234567890']);
    }

    public function test_home_and_contact_use_saved_default(): void
    {
        $this->get('/')->assertOk()->assertSee('Default office')->assertSee('office@example.com');
        $this->get('/action')->assertStatus(301)->assertRedirect(route('contact'));
        $this->get('/contact-us')->assertOk()->assertSee('Default office')->assertSee('mailto:office@example.com', false);
    }

    public function test_journal_contact_uses_override_and_inherits_empty_fields(): void
    {
        DB::table('contact_details')->insert(['journal_id'=>1,'address'=>'Alpha office','email'=>null,'phone'=>'']);
        $response = $this->get('/contact-us?journal=alpha')->assertOk()->assertSee('Alpha office')->assertSee('office@example.com')->assertDontSee('Default office');
        $this->assertSame(2, substr_count($response->getContent(), 'Alpha office'));
        $this->get('/contact-us?journal=beta')->assertOk()->assertSee('Default office')->assertDontSee('Alpha office');
        $this->get('/contact-us?journal=missing')->assertNotFound();
    }

    public function test_remote_contact_and_unavailable_api_fallback(): void
    {
        config(['services.public_api.base_url'=>'https://api.example.test/api']);
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake(['*/contact-details/1'=>Http::response(['contact'=>['address'=>'Remote office','email'=>'remote@example.com','phone'=>'+123']]), '*'=>Http::response([], 503)]);
        $this->assertSame('Remote office', (new ContactDetails)->resolve(1)['address']);
        $this->assertSame('Default office', (new ContactDetails)->resolve(2)['address']);
    }

    public function test_original_contact_remains_before_migration(): void
    {
        Schema::drop('contact_details');
        $this->assertSame(ContactDetails::DEFAULTS, (new ContactDetails)->resolve());
    }
}
