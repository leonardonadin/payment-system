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
    public function getDefaultWallet($user_id)
    {
        return $this->walletRepository->getDefaultWallet($user_id);
    }

    /**
     * Get all wallets of a user.
     *
     * @return Collection
     */
    public function getWallets()
    {
        return $this->walletRepository->getWallets(auth()->user()->id);
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
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->user()->id;
        }
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
        if ($this->getWallets(auth()->user()->id)->count() == 1) {
            return ['error' => 'You cannot delete your last wallet.'];
        }

        return $this->walletRepository->deleteWallet($wallet_id);
    }
}
