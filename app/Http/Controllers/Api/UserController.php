<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Http\Requests\Api\UserFilterRequest;

class UserController extends ApiController
{
    public function __construct(private UserServiceContract $userService, private AuthServiceContract $authService)
    {
    }

    public function index(UserFilterRequest $request)
    {
        return $this->jsonReponse($this->userService->getFilteredUsers(
            $this->authService->getAuthUserId(),
            $request->validated()
        ));
    }
}
