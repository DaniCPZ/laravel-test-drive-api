<?php

namespace Tests\Feature;

use Google\Client;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\WebService;
use Mockery\MockInterface;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setup(): void
    {
        parent::setUp();
        $this->user =  User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_a_user_can_connect_to_a_service_and_token_is_stored()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')->andReturn('http://localhost');
        });

        $response = $this->getJson(route('web-service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertEquals('http://localhost', $response['url']);
        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn(['access_token' => 'fake-token']);
        });

        $res = $this->postJson(route('web-service.callback'), [
            'code' => 'dummyCode'
        ])->assertCreated();

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id,
            'token' => json_encode(['access_token' => 'fake-token'])
        ]);
    }

    public function test_data_of_a_week_can_be_stored_on_google_drive()
    {
        Task::factory()->create(['created_at' => now()->subDays(2)]);
        Task::factory()->create(['created_at' => now()->subDays(3)]);
        Task::factory()->create(['created_at' => now()->subDays(4)]);
        Task::factory()->create(['created_at' => now()->subDays(6)]);

        Task::factory()->create(['created_at' => now()->subDays(10)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });

        $web_service =  WebService::factory()->create();
        $this->postJson(route('web-service.store', $web_service->id))->assertCreated();
    }
}