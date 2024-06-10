<?php
namespace Tests\Unit\Services;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Services\UserService;
use App\Enums\UserTypes;
use Tests\Unit\UnitTestCase;

class UserServiceTest extends UnitTestCase
{
    protected $userRepository;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryContract::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testGetAll()
    {
        $users = ['user1', 'user2'];
        $this->userRepository->method('getAll')->willReturn($users);
        $this->assertEquals($users, $this->userService->getAll());
    }

    public function testGetUser()
    {
        $user = 'user1';
        $this->userRepository->method('getUser')->willReturn($user);
        $this->assertEquals($user, $this->userService->getUser(1));
    }

    public function testGetFilteredUsers()
    {
        $userByDocument = 'user1';
        $userByEmail = 'user2';
        $this->userRepository->method('getUserByDocument')->willReturn($userByDocument);
        $this->userRepository->method('getUserByEmail')->willReturn($userByEmail);
        $this->assertEquals([$userByDocument], $this->userService->getFilteredUsers(['document' => 'doc']));
        $this->assertEquals([$userByEmail], $this->userService->getFilteredUsers(['email' => 'email']));
    }

    public function testCreateUser()
    {
        $user = 'user1';
        $this->userRepository->method('createUser')->willReturn($user);
        $this->assertEquals($user, $this->userService->createUser(['name' => 'User 1']));
    }

    public function testUpdateUser()
    {
        $user = 'user1';
        $this->userRepository->method('updateUser')->willReturn($user);
        $this->assertEquals($user, $this->userService->updateUser(1, ['name' => 'User 1']));
    }

    public function testCanMakeTransactions()
    {
        $user = (object) ['type' => UserTypes::COMMON];
        $this->userRepository->method('getUser')->willReturn($user);
        $this->assertTrue($this->userService->canMakeTransactions(1));
    }
}
