<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Services\AuthServiceContract;
use App\Http\Requests\Api\Auth\RegisterRequest;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        return response()->json($this->authService->registerUser($validated), 201);
    }
}
