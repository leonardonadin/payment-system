<?php

namespace Tests\Unit\Services;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\UserServiceContract;
use App\Enums\UserTypes;
use App\Services\UserService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;
    protected $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryContract::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function test_getAll_returns_all_users()
    {
        $this->userRepository->method('getAll')->willReturn(User::factory()->count(3)->make());
        $users = $this->userService->getAll();
        $this->assertCount(3, $users);
    }

    public function test_getUser_returns_user_by_id()
    {
        $user = User::factory()->make(['id' => 1]);
        $this->userRepository->method('getUser')->willReturn($user);
        $result = $this->userService->getUser(1);
        $this->assertEquals($user->id, $result->id);
    }

    public function test_getFilteredUsers_returns_users_by_filters()
    {
        $user = User::factory()->make(['document' => '123456789']);
        $this->userRepository->method('getUserByDocument')->willReturn($user);
        $result = $this->userService->getFilteredUsers(1, ['document' => '123456789']);
        $this->assertEquals($user->document, $result[0]->document);
    }

    public function test_createUser_creates_a_new_user()
    {
        $user = User::factory()->make();
        $this->userRepository->method('createUser')->willReturn($user);
        $result = $this->userService->createUser($user->toArray());
        $this->assertEquals($user->email, $result->email);
    }

    public function test_updateUser_updates_a_user()
    {
        $user = User::factory()->make(['id' => 1, 'name' => 'Old Name']);
        $this->userRepository->method('getUser')->willReturn($user);
        $this->userRepository->method('updateUser')->willReturn($user->fill(['name' => 'New Name']));
        $result = $this->userService->updateUser(1, ['name' => 'New Name']);
        $this->assertEquals('New Name', $result->name);
    }

    public function test_canMakeTransactions_returns_true_for_common_user()
    {
        $user = User::factory()->make(['type' => 'common']);
        $user->id = 1;
        $this->userRepository->method('getUser')->willReturn($user);
        $result = $this->userService->canMakeTransactions($user->id);
        $this->assertTrue($result);
    }

    public function test_canMakeTransactions_returns_false_for_non_common_user()
    {
        $user = User::factory()->make(['type' => 'merchant']);
        $user->id = 1;
        $this->userRepository->method('getUser')->willReturn($user);
        $result = $this->userService->canMakeTransactions($user->id);
        $this->assertFalse($result);
    }
}
