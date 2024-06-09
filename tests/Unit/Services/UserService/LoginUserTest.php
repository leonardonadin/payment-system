<?php

namespace Tests\Unit\Services\UserService;

class LoginUserTest extends UserServiceTest
{
    public function test_login_calls_attempt_on_auth_with_correct_parameters_and_returns_user_on_success()
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
        ];
        $expectedUser = (object) $data;
        $this->authRepository
            ->expects($this->once())
            ->method('attemptLogin')
            ->with($data)
            ->willReturn(true);
        $this->authRepository
            ->expects($this->once())
            ->method('getAuthUser')
            ->willReturn($expectedUser);

        $token = $this->authService->loginUser($data);

        $this->assertArrayHasKey('user', $token);
    }

    public function test_login_returns_null_on_failure()
    {
        $data = [
            'email' => 'email@email.com',
            'password' => 'password',
        ];
        $this->authRepository
            ->expects($this->once())
            ->method('attemptLogin')
            ->with($data)
            ->willReturn(false);

        $user = $this->authService->loginUser($data);
        $this->assertFalse($user);
    }
}
