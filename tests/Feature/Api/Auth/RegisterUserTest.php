<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\ApiTestCase;

class RegisterUserTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_when_user_registers_then_user_is_created(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test@test.com',
            'document' => '13483907009',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'test@test.com',
            'document' => '13483907009',
            'type' => 'common',
        ]);
    }

    public function test_when_user_registers_with_type_then_user_is_created_with_type(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test1@test.com',
            'document' => '12686472000129',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'type' => 'merchant',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'test1@test.com',
            'document' => '12686472000129',
            'type' => 'merchant',
        ]);
    }

    public function test_when_user_registers_then_create_wallet(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test2@test.com',
            'document' => '13483907009',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'test2@test.com',
            'document' => '13483907009',
            'type' => 'common',
        ]);
        $user_id = $response->json('id');
        $this->assertDatabaseHas('wallets', [
            'balance' => 0,
            'user_id' => $user_id,
        ]);
    }
}
