<?php

namespace Tests\Feature\Api\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\ApiTestCase;

class UpdateProfileTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_when_user_updates_his_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum');

        $response = $this->putJson('/api/profile', [
            'name' => 'John Doe',
            'email' => 'john@test.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'John Doe',
                'email' => 'john@test.com'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@test.com'
        ]);
    }
}
