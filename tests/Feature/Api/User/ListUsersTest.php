<?php

namespace Tests\Feature\Api\User;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\ApiTestCase;

class ListUsersTest extends ApiTestCase
{
    use RefreshDatabase;

    private $users;
    private $user;
    private $wallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = User::factory()->count(10)->create();

        $this->user = User::factory()->create();
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 100
        ]);

        $this->users->map(function ($user) {
            $wallet = Wallet::factory()->create([
                'user_id' => $user->id,
                'balance' => 100
            ]);
            Transaction::factory()->create([
                'payer_wallet_id' => $this->wallet->id,
                'payee_wallet_id' => $wallet->id,
                'amount' => 2
            ]);
        });

        $this->actingAs($this->user);
    }

    public function test_when_user_lists_users_by_document_then_return_user(): void
    {
        $response = $this->get('/api/users?document=' . $this->users->first()->document);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'document' => $this->users->first()->document
        ]);
    }

    public function test_when_user_lists_users_by_email_then_return_user(): void
    {
        $response = $this->get('/api/users?email=' . $this->users->first()->email);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'email' => $this->users->first()->email
        ]);
    }

    public function test_when_user_list_users_then_return_users_knowed(): void
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }
}
