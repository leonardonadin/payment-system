<?php

namespace Tests\Unit\Services\AuthService;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Services\AuthService;
use Tests\Unit\UnitTestCase;

class AuthServiceTest extends UnitTestCase
{
    protected $authRepository;
    protected $authService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authRepository = $this->createMock(AuthRepositoryContract::class);
        $this->authService = new AuthService($this->authRepository);
    }
}
