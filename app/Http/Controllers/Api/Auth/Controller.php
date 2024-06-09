<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Services\AuthServiceContract;

abstract class Controller extends \App\Http\Controllers\Controller
{
    public function __construct(protected AuthServiceContract $authService)
    {
    }
}
