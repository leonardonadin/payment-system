<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = $this->authService->loginUser($validated);

        if ($user) {
            return response()->json($user);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
