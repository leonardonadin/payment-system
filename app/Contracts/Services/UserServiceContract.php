<?php

namespace App\Contracts\Services;

interface UserServiceContract
{
    public function getAll();
    public function getUser($user_id);
    public function getFilteredUsers($filters);
    public function createUser($data);
    public function canMakeTransactions($user_id);
}
