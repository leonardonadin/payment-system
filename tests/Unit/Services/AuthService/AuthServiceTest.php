<?php

namespace Tests\Unit\Services\AuthService;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Tests\Unit\UnitTestCase;

class AuthServiceTest extends UnitTestCase
{
    protected $authRepository;
    protected $userRepository;
    protected $authService;
    protected $userService;
    protected $walletService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryContract::class);
        $this->userService = $this->createMock(UserServiceContract::class);

        $this->walletService = $this->createMock(WalletServiceContract::class);

        $this->authRepository = $this->createMock(AuthRepositoryContract::class);
        $this->authService = new AuthService(
            $this->authRepository,
            $this->userService,
            $this->walletService
        );

        DB::shouldReceive('beginTransaction');
        DB::shouldReceive('commit');
        DB::shouldReceive('rollBack');
    }
}
