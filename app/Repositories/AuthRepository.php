<?php

namespace App\Repositories;

use App\Contracts\Repositories\AuthRepositoryContract;

class AuthRepository implements AuthRepositoryContract
{

    public function attemptLogin($data)
    {
        return auth()->attempt($this->extractCredentials($data));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return auth()->logout();
    }

    public function checkAuth()
    {
        return auth()->check();
    }

    public function createAuthToken()
    {
        $token = auth()->user()->createToken('authToken');

        return [
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at,
        ];
    }

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
