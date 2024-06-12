<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Services\AuthServiceContract;

abstract class AuthController extends \App\Http\Controllers\Api\ApiController
{
    public function __construct(protected AuthServiceContract $authService)
    {
    }
}
