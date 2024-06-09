<?php

namespace App\Contracts\Repositories;

interface UserRepositoryContract
{
    public function getAll();
    public function getUser($id);
    public function getUserByEmail($email);
    public function getUserByDocument($document);
    public function getKnownedUsers($user_id);
    public function createUser($data);
    public function updateUser($user, $data);
}
