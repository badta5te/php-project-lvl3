<?php

namespace Tests\Feature;

use Facade\Ignition\Support\FakeComposer;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainControllerTest extends TestCase
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

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
        $response->assertSee($this->domain);
    }

    public function testStore()
    {
        $this->withoutMiddleware();

        $data = [
            'domain' => [
                'name' => $this->url
            ]
        ];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', ['id' => $this->id, 'name' => $this->domain]);
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));
        $response->assertOk();
    }
}
