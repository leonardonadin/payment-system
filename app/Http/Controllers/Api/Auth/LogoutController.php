<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

class LogoutController extends AuthController
{

    /**
     * Perform user logout.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->authService->logoutUser();

        return $this->jsonReponse([
            'message' => 'User logged out successfully',
        ]);
    }
}
