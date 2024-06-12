<?php

namespace App\Services;

use App\Contracts\Repositories\WalletRepositoryContract;
use App\Contracts\Services\WalletServiceContract;

class WalletService implements WalletServiceContract
{
    public function __construct(private WalletRepositoryContract $walletRepository)
    {
    }

    /**
     * Get the default wallet of a user.
     *
     * @param int $user_id
     * @return Wallet
     */
    public function getUserDefaultWallet($user_id)
    {
        return $this->walletRepository->getUserDefaultWallet($user_id);
    }

    /**
     * Get all wallets of a user.
     *
     * @param int $user_id
     * @return Collection
     */
    public function getUserWallets($user_id)
    {
        return $this->walletRepository->getUserWallets($user_id);
    }

    /**
     * Get a wallet by its ID.
     *
     * @param int $wallet_id
     * @return Wallet
     */
    public function getWallet($wallet_id)
    {
        return $this->walletRepository->getWallet($wallet_id);
    }

    /**
     * Create a new wallet.
     *
     * @param array $data
     * @return Wallet
     */
    public function createWallet($data)
    {
        return $this->walletRepository->createWallet($data);
    }

    /**
     * Update the balance of a wallet.
     *
     * @param int $wallet_id
     * @param float $balance
     * @return Wallet
     */
    public function updateWalletBalance($wallet_id, $balance)
    {
        return $this->walletRepository->updateWallet($wallet_id, ['balance' => $balance]);
    }

    /**
     * Adjust the balance of a wallet by increasing or decreasing it.
     *
     * @param int $wallet_id
     * @param string $type in|out
     * @param float $amount
     * @return Wallet
     */
    public function adjustWalletBalance($wallet_id, $type, $amount)
    {
        $wallet = $this->getWallet($wallet_id);

        if ($type == 'out' && $wallet->balance < $amount) {
            return false;
        }

        if ($type == 'in') {
            $balance = $wallet->balance + $amount;
        } else {
            $balance = $wallet->balance - $amount;
        }

        return $this->updateWalletBalance($wallet_id, $balance);
    }

    /**
     * Delete a wallet.
     *
     * @param int $wallet_id
     * @return bool
     */
    public function deleteWallet($wallet_id)
    {
        $wallet = $this->getWallet($wallet_id);

        if ($this->getUserWallets($wallet->user_id)->count() == 1) {
            return ['error' => 'You cannot delete your last wallet.'];
        }

        if ($wallet->balance > 0) {
            return ['error' => 'You cannot delete a wallet with a balance.'];
        }

        if ($wallet->transactions->count() > 0) {
            return ['error' => 'You cannot delete a wallet with transactions.'];
        }

        return $this->walletRepository->deleteWallet($wallet_id);
    }
}
