<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Http\Requests\Api\ProfileUpdateRequest;

class ProfileController extends ApiController
{
    public function __construct(private UserServiceContract $userService, private AuthServiceContract $authService)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return $this->jsonReponse($this->authService->getAuthUser());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        return $this->jsonReponse($this->userService->updateUser(
            $this->authService->getAuthUserId(),
            $request->validated()
        ));
    }
}
