<?php

namespace App\Contracts\Repositories;

interface AuthRepositoryContract
{
    public function attemptLogin($data);
    public function logout();
    public function checkAuth();
    public function createAuthToken();
    public function getAuthUser();
}
