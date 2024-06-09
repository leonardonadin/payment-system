<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\UserServiceContract;
use App\Enums\UserTypes;

class UserService implements UserServiceContract
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    /**
     * Get all users.
     *
     * @return User[]
     */
    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get a user by its ID.
     *
     * @param int $user_id
     * @return User
     */
    public function getUser($user_id)
    {
        return $this->userRepository->getUser($user_id);
    }

    /**
     * Get users by filters (document or email) or knowned users.
     *
     * @param array $filters
     * @return User[]
     */
    public function getFilteredUsers($filters)
    {
        if (isset($filters['document'])) {
            return [$this->userRepository->getUserByDocument($filters['document'])];
        }

        if (isset($filters['email'])) {
            return [$this->userRepository->getUserByEmail($filters['email'])];
        }

        return $this->userRepository->getKnownedUsers(auth()->id());
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser($data)
    {
        return $this->userRepository->createUser($data);
    }

    /**
     * Update a user.
     *
     * @param int $user_id
     * @param array $data
     */
    public function updateUser($user_id, $data)
    {
        return $this->userRepository->updateUser($user_id, $data);
    }

    /**
     * Check if user can make transactions.
     *
     * @param int $user_id
     * @return bool
     */
    public function canMakeTransactions($user_id)
    {
        $user = $this->getUser($user_id);

        return $user->type == UserTypes::COMMON;
    }
}
