<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\ApiTestCase;

class UpdateWalletTest extends ApiTestCase
{
    use RefreshDatabase;

    private $user;
    private $wallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 100
        ]);

        $this->actingAs($this->user);
    }

    public function test_when_user_increases_wallet_then_wallet_balance_is_increased(): void
    {
        $previousBalance = $this->wallet->balance;
        $response = $this->put('/api/wallets/' . $this->wallet->id, [
            'type' => 'in',
            'amount' => 20,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('wallets', [
            'id' => $this->wallet->id,
            'user_id' => $this->user->id,
            'balance' => $previousBalance + 20,
        ]);
    }

    public function test_when_user_decreases_wallet_then_wallet_balance_is_decreased(): void
    {
        $previousBalance = $this->wallet->balance;
        $response = $this->put('/api/wallets/' . $this->wallet->id, [
            'type' => 'out',
            'amount' => 20,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('wallets', [
            'id' => $this->wallet->id,
            'user_id' => $this->user->id,
            'balance' => $previousBalance - 20,
        ]);
    }
}
