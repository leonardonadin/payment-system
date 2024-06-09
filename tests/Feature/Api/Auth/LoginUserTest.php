<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Api\ApiTestCase;

class LoginUserTest extends ApiTestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_when_user_logs_in_then_user_is_logged_in(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function test_when_user_logs_in_with_invalid_credentials_then_user_is_not_logged_in(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'invalid@test.com',
            'password' => 'invalid',
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }
}
