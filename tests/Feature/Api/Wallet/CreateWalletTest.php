<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\ApiTestCase;

class CreateWalletTest extends ApiTestCase
{
    use RefreshDatabase;

    private $user;
    private $wallet;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_when_unauthenticated_user_creates_wallet_then_unauthorized_response_is_returned(): void
    {
        $response = $this->post('/api/wallets', [
            'name' => 'Test Wallet',
            'balance' => 100,
        ]);

        $response->assertUnauthorized();
    }

    public function test_when_user_creates_wallet_then_wallet_is_created(): void
    {
        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $response = $this->post('/api/wallets', [
            'name' => 'Test Wallet',
            'balance' => 100,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $this->user->id,
            'name' => 'Test Wallet',
            'balance' => 100,
        ]);
    }
}
