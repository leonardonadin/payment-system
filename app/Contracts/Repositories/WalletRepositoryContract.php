<?php

namespace App\Contracts\Repositories;

interface WalletRepositoryContract
{
    public function getWallet($wallet_id);
    public function getUserWallets($user_id);
    public function getUserDefaultWallet($user_id);
    public function createWallet($data);
    public function updateWallet($wallet_id, $data);
    public function deleteWallet($wallet_id);
}
