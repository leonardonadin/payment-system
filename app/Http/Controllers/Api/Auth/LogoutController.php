<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

class LogoutController extends Controller
{

    /**
     * Perform user logout.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->authService->logoutUser();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }
}
