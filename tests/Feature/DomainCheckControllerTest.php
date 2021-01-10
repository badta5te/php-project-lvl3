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
        $this->domain = strtolower(parse_url($this->url, PHP_URL_HOST));

        $this->id = DB::table('domains')->insertGetId([
            'name' => $this->domain,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function testStore()
    {
        $this->withoutMiddleware();

        Http::fake();

        $response = $this->post(route('domain.checks.store', $this->id));
        $response->assertStatus(302);
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->id,
            'status_code' => '200'
        ]);
    }
}
