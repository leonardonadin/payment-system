<?php

namespace Tests\Feature\Api\Transaction;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\Feature\Api\ApiTestCase;

class CreateTransactionTest extends ApiTestCase
{
    use RefreshDatabase;

    private $payer_user;
    private $payer_wallet;
    private $payee_user;
    private $payee_wallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->payer_user = User::factory()->create([
            'email' => 'payer@test.com'
        ]);
        $this->payer_wallet = Wallet::factory()->create([
            'user_id' => $this->payer_user->id,
            'balance' => 100
        ]);

        $this->payee_user = User::factory()->create([
            'email' => 'payee@test.com',
            'document' => '83531159054'
        ]);
        $this->payee_wallet = Wallet::factory()->create([
            'user_id' => $this->payee_user->id,
            'balance' => 0
        ]);

        $this->actingAs($this->payer_user);
    }

    public function test_when_payer_is_common_user_and_payee_is_common_user_then_transaction_is_created(): void
    {
        $this->mock(\App\Services\Transaction\AuthorizationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('authorizeTransaction')->andReturn(true);
        });

        $response = $this->post('/api/transactions', [
            'payee' => [
                'id' => $this->payee_user->id
            ],
            'amount' => 10,
            'description' => 'Test transaction'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'payer_wallet_id' => $this->payer_wallet->id,
            'payee_wallet_id' => $this->payee_wallet->id,
            'amount' => 10,
            'description' => 'Test transaction'
        ]);
    }

    public function test_when_payer_is_common_user_and_payee_is_common_user_and_payer_has_insufficient_funds_then_transaction_is_not_created(): void
    {
        $response = $this->post('/api/transactions', [
            'payee' => [
                'id' => $this->payee_user->id
            ],
            'amount' => 101,
            'description' => 'Test transaction'
        ]);

        $response->assertStatus(400);
        $this->assertDatabaseMissing('transactions', [
            'payer_wallet_id' => $this->payer_wallet->id,
            'payee_wallet_id' => $this->payee_wallet->id,
            'amount' => 101,
            'description' => 'Test transaction'
        ]);
    }

    public function test_when_payer_is_merchant_user_and_payee_is_common_user_then_transaction_is_not_created(): void
    {
        $payer_user = User::factory()->create([
            'email' => 'merchant_payer@test.com',
            'type' => 'merchant',
            'document' => '47897200000152'
        ]);

        $this->actingAs($payer_user);

        $response = $this->post('/api/transactions', [
            'payee' => [
                'id' => $this->payee_user->id
            ],
            'amount' => 10,
            'description' => 'Test transaction'
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('transactions', [
            'payer_wallet_id' => $this->payer_wallet->id,
            'payee_wallet_id' => $this->payee_wallet->id,
            'amount' => 10,
            'description' => 'Test transaction'
        ]);
    }
}
