<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Services\DeeplinkService;
use Tests\TestCase;

class ApiRegisterTest extends TestCase
{
    private DeeplinkService $deeplinkService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        config(['batid.deeplink_secret' => 'test-secret']);
        config(['batid.deeplink_ttl' => 600]);
        $this->deeplinkService = new DeeplinkService();
    }

    public function test_register_creates_subscriber(): void
    {
        $token = $this->deeplinkService->generateRegisterToken('+41791234567', '@newBat');

        $response = $this->getJson('/api/register?token=' . $token);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'subscriber' => [
                    'bat_id' => '@newBat',
                    'phone' => '+41791234567',
                ],
            ]);

        $this->assertDatabaseHas('subscribers', [
            'bat_id' => '@newBat',
            'phone' => '+41791234567',
        ]);
    }

    public function test_register_missing_token(): void
    {
        $response = $this->getJson('/api/register');

        $response->assertStatus(400)
            ->assertJson(['code' => 'missing_token']);
    }

    public function test_register_invalid_token(): void
    {
        $response = $this->getJson('/api/register?token=invalid.token');

        $response->assertStatus(401)
            ->assertJson(['code' => 'invalid_token']);
    }

    public function test_register_duplicate_bat_id(): void
    {
        Subscriber::factory()->create(['bat_id' => '@existBat']);

        $token = $this->deeplinkService->generateRegisterToken('+41799999999', '@existBat');
        $response = $this->getJson('/api/register?token=' . $token);

        $response->assertStatus(409)
            ->assertJson(['code' => 'bat_id_exists']);
    }

    public function test_register_duplicate_phone(): void
    {
        Subscriber::factory()->create(['phone' => '+41791111111']);

        $token = $this->deeplinkService->generateRegisterToken('+41791111111', '@newBat2');
        $response = $this->getJson('/api/register?token=' . $token);

        $response->assertStatus(409)
            ->assertJson(['code' => 'phone_exists']);
    }

    public function test_register_duplicate_soft_deleted(): void
    {
        $subscriber = Subscriber::factory()->create(['bat_id' => '@deleted']);
        $subscriber->delete();

        $token = $this->deeplinkService->generateRegisterToken('+41792222222', '@deleted');
        $response = $this->getJson('/api/register?token=' . $token);

        $response->assertStatus(409)
            ->assertJson(['code' => 'bat_id_exists']);
    }
}
