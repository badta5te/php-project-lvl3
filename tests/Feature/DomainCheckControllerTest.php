<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckControllerTest extends TestCase
{
    protected $id;
    protected $domain;
    protected $url;

    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();
        $this->url = $faker->url;
        $urlParts = parse_url($this->url);
        ['scheme' => $scheme, 'host' => $host] = $urlParts;
        $this->domain = "{$scheme}://{$host}";


        $this->id = DB::table('domains')->insertGetId([
            'name' => $this->domain,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function testStore()
    {
        $this->withoutMiddleware();

        $testHtml = file_get_contents(__DIR__ . '/../fixtures/test.html');
        Http::fake([
            $this->domain => Http::response($testHtml, 200)
        ]);

        $response = $this->post(route('domains.checks.store', $this->id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->id,
            'h1' => 'test h1',
            'keywords' => 'test keywords',
            'description' => 'test description',
            'status_code' => '200'
        ]);
    }
}
