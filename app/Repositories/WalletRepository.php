<?php

namespace App\Repositories;

use App\Contracts\Repositories\WalletRepositoryContract;
use App\Models\Wallet;

class WalletRepository implements WalletRepositoryContract
{
    public function getDefaultWallet($user_id)
    {
        return Wallet::where('user_id', $user_id)->first();
    }

    public function getWallets($user_id)
    {
        return Wallet::where('user_id', $user_id)->get();
    }

    public function getWallet($wallet_id)
    {
        return Wallet::find($wallet_id);
    }

    public function createWallet($data)
    {
        $wallet = new Wallet();
        $wallet->user_id = $data['user_id'];
        $wallet->name = $data['name'] ?? 'Default Wallet';
        $wallet->balance = $data['balance'] ?? 0;
        $wallet->save();

        return $wallet;
    }

    public function updateWallet($wallet_id, $data)
    {
        $wallet = Wallet::find($wallet_id);
        if (isset($data['balance'])) {
            $wallet->balance = $data['balance'];
        }
        $wallet->save();

        return $wallet;
    }

    public function deleteWallet($wallet_id)
    {
        Wallet::destroy($wallet_id);
    }
}
