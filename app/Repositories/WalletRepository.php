<?php

namespace App\Repositories;

use App\Contracts\Repositories\WalletRepositoryContract;
use App\Models\Wallet;

class WalletRepository implements WalletRepositoryContract
{

    /**
     * Get the default wallet from a user.
     *
     * @param int $user_id
     * @return Wallet
     */
    public function getUserDefaultWallet($user_id)
    {
        return Wallet::where('user_id', $user_id)->first();
    }

    /**
     * Get all wallets from a user.
     *
     * @param int $user_id
     * @return Wallet[]
     */
    public function getUserWallets($user_id)
    {
        return Wallet::where('user_id', $user_id)->get();
    }

    /**
     * Get a wallet by its ID.
     *
     * @param int $wallet_id
     * @return Wallet
     */
    public function getWallet($wallet_id)
    {
        return Wallet::find($wallet_id);
    }

    /**
     * Create a new wallet.
     *
     * @param array $data
     * @return Wallet
     */
    public function createWallet($data)
    {
        $wallet = new Wallet();
        $wallet->user_id = $data['user_id'];
        $wallet->name = $data['name'] ?? 'Default Wallet';
        $wallet->balance = $data['balance'] ?? 0;
        $wallet->save();

        return $wallet;
    }

    /**
     * Update a wallet.
     *
     * @param int $wallet_id
     * @param array $data
     * @return Wallet
     */
    public function updateWallet($wallet_id, $data)
    {
        $wallet = Wallet::find($wallet_id);
        if (isset($data['balance'])) {
            $wallet->balance = $data['balance'];
        }
        $wallet->save();

        return $wallet;
    }

    /**
     * Delete a wallet.
     *
     * @param int $wallet_id
     */
    public function deleteWallet($wallet_id)
    {
        Wallet::destroy($wallet_id);
    }
}
