<?php

namespace Tests\Unit\Services\AuthService;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Services\AuthService;
use Tests\Unit\UnitTestCase;

class AuthServiceTest extends UnitTestCase
{
    protected $authRepository;
    protected $authService;
    protected $userService;
    protected $walletService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authRepository = $this->createMock(AuthRepositoryContract::class);
        $this->userService = $this->createMock(UserServiceContract::class);
        $this->walletService = $this->createMock(WalletServiceContract::class);

        $this->authService = new AuthService(
            $this->authRepository,
            $this->userService,
            $this->walletService
        );
    }
}
