<?php

namespace App\Contracts\Services;

interface WalletServiceContract
{
    public function getWallet($wallet_id);
    public function getUserDefaultWallet($user_id);
    public function getUserWallets($user_id);
    public function createWallet($data);
    public function updateWalletBalance($wallet_id, $balance);
    public function adjustWalletBalance($wallet_id, $type, $amount);
    public function deleteWallet($wallet_id);
}
