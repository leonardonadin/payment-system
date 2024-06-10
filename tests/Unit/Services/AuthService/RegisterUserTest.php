<?php

namespace Tests\Unit\Services\AuthService;

class RegisterUserTest extends AuthServiceTest
{

    public function test_registerUser_calls_createUser_on_repository_with_correct_parameters_and_returns_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'email@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'document' => '81760776076',
            'type' => 'common',
        ];

        $expectedUser = (object) $data;

        $user = $this->authService->registerUser($data);

        $this->assertEquals($expectedUser->name, $user->name);
        $this->assertEquals($expectedUser->email, $user->email);
        $this->assertEquals($expectedUser->document, $user->document);
    }
}
