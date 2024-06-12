<?php

namespace App\Repositories;

use App\Contracts\Repositories\AuthRepositoryContract;

class AuthRepository implements AuthRepositoryContract
{

    /**
     * Attempt to login a user.
     *
     * @param array $data
     * @return bool
     */
    public function attemptLogin($data)
    {
        return auth()->attempt($this->extractCredentials($data));
    }

    /**
     * Logout a user.
     *
     * @return bool
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return auth()->logout();
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    public function checkAuth()
    {
        return auth()->check();
    }

    /**
     * Create a new authentication token.
     *
     * @return array
     */
    public function createAuthToken()
    {
        $token = auth()->user()->createToken('authToken');

        return [
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at,
        ];
    }

    /**
     * Get the authenticated user.
     *
     * @return mixed
     */
    public function getAuthUser()
    {
        return auth()->user();
    }

    private function extractCredentials($data)
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }
}
