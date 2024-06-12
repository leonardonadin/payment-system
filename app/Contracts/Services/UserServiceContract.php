<?php

namespace App\Contracts\Services;

interface UserServiceContract
{
    public function getAll();
    public function getUser(int $user_id);
    public function getFilteredUsers(int $user_id, array $filters);
    public function createUser(array $data);
    public function updateUser(int $user_id, array $data);
    public function canMakeTransactions(int $user_id);
}
