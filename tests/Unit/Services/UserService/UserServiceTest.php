<?php

namespace Tests\Unit\Services\UserService;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Services\AuthService;
use App\Services\UserService;
use Tests\Unit\UnitTestCase;

class UserServiceTest extends UnitTestCase
{
    protected $authRepository;
    protected $userRepository;
    protected $authService;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authRepository = $this->createMock(AuthRepositoryContract::class);
        $this->authService = new AuthService($this->authRepository);

        $this->userRepository = $this->createMock(UserRepositoryContract::class);
        $this->userService = new UserService($this->userRepository);
    }
}
