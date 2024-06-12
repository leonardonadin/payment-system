<?php

namespace App\Contracts\Services;

interface AuthServiceContract
{
    public function registerUser($data);
    public function loginUser($data);
    public function logoutUser();
    public function getAuthUser();
    public function getAuthUserId();
}
