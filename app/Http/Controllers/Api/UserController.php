<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserFilterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserServiceContract $userService, private AuthServiceContract $authService)
    {
    }

    public function index(UserFilterRequest $request)
    {
        return response()->json($this->userService->getFilteredUsers($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return response()->json($this->authService->getAuthUser());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }
}
