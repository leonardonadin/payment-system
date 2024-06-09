<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Enums\UserTypes;
use App\Models\User;

class UserRepository implements UserRepositoryContract
{
    /**
     * Get all users.
     *
     * @return User[]
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Get a user by id.
     *
     * @param int $id
     * @return User
     */
    public function getUser($id)
    {
        return User::find($id);
    }

    /**
     * Get a user by email.
     *
     * @param string $email
     * @return User
     */
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get a user by document.
     *
     * @param string $document
     * @return User
     */
    public function getUserByDocument($document)
    {
        return User::where('document', $document)->first();
    }

    /**
     * Get known users by user.
     *
     * @param int $user_id
     * @return User[]
     */
    public function getKnownedUsers($user_id)
    {
        return User::whereIn('id', function($q) use ($user_id) {
            $q->select('wallets.user_id')
                ->from('wallets')
                ->whereIn('wallets.id', function($q) use ($user_id) {
                    $q->select('transactions.payee_wallet_id')
                        ->from('transactions')
                        ->join('wallets', 'transactions.payer_wallet_id', '=', 'wallets.id')
                        ->where('wallets.user_id', $user_id);
                });
        })->get();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->document = $data['document'];
        $user->type = $data['type'] ?? UserTypes::COMMON;
        $user->country_code = $data['country_code'] ?? 'BR';
        $user->save();

        return $user;
    }

    /**
     * Update a user.
     *
     * @param int $user_id
     * @param array $data
     * @return User
     */
    public function updateUser($user_id, $data)
    {
        $user = $this->getUser($user_id);

        if (!$user) {
            return null;
        }
        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;
        $user->document = $data['document'] ?? $user->document;
        $user->type = $data['type'] ?? $user->type;
        $user->country_code = $data['country_code'] ?? $user->country_code;
        if (isset($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        return $user;
    }
}
