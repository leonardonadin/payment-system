<?php

namespace App\Services;

use App\Contracts\Repositories\AuthRepositoryContract;
use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Trait\Services\ReversibleActionsTrait;

class AuthService implements AuthServiceContract
{
    use ReversibleActionsTrait;

    public function __construct(
        private AuthRepositoryContract $authRepository,
        private UserServiceContract $userService,
        private WalletServiceContract $walletService
    )
    {
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function registerUser($data)
    {
        return $this->persistOnSuccess(function () use ($data) {
            $user = $this->userService->createUser($data);

            $wallet = $this->walletService->createWallet([
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }

    /**
     * Login a user.
     *
     * @param array $data
     * @return array|bool
     */
    public function loginUser($data)
    {
        if ($this->authRepository->attemptLogin($data)) {
            $token = $this->authRepository->createAuthToken();

            $token['user'] = $this->authRepository->getAuthUser();

            return $token;
        }

        return false;
    }

    /**
     * Logout a user.
     *
     * @return bool
     */
    public function logoutUser()
    {
        return $this->authRepository->logout();
    }

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    public function getAuthUser()
    {
        return $this->authRepository->getAuthUser();
    }

    /**
     * Get the authenticated user ID.
     *
     * @return int
     */
    public function getAuthUserId()
    {
        return $this->authRepository->getAuthUser()->id;
    }
}
