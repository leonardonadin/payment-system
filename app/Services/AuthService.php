<?php

namespace App\Services;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Trait\Services\ReversibleActionsTrait;
use Illuminate\Support\Facades\DB;

class AuthService implements AuthServiceContract
{
    use ReversibleActionsTrait;

    public function __construct(private AuthRepositoryContract $authRepository)
    {
    }

    public function registerUser($data)
    {
        return $this->persistOnSuccess(function () use ($data) {
            $user = app()->makeWith(UserServiceContract::class)->createUser($data);

            $wallet = app()->makeWith(WalletServiceContract::class)->createWallet([
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }

    public function loginUser($data)
    {
        if ($this->authRepository->attemptLogin($data)) {
            $token = $this->authRepository->createAuthToken();

            $token['user'] = $this->authRepository->getAuthUser();

            return $token;
        }

        return false;
    }

    public function logoutUser()
    {
        return $this->authRepository->logout();
    }

    public function getAuthUser()
    {
        return $this->authRepository->getAuthUser();
    }
}
