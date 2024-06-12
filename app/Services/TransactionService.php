<?php

namespace App\Services;

use App\Contracts\Repositories\TransactionRepositoryContract;
use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\Transaction\AuthorizationServiceContract;
use App\Contracts\Services\Transaction\NotificationServiceContract;
use App\Contracts\Services\TransactionServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Trait\Services\ReversibleActionsTrait;

class TransactionService implements TransactionServiceContract
{
    use ReversibleActionsTrait;

    public function __construct(
        private TransactionRepositoryContract $transactionRepository,
        private WalletServiceContract $walletService,
        private UserServiceContract $userService,
        private AuthServiceContract $authService,
        private AuthorizationServiceContract $authorizationService,
        private NotificationServiceContract $notificationService
    )
    {
    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return Transaction|array Transaction created or error message
     */
    public function createTransaction($data)
    {
        if (!isset($data['payer']) || !isset($data['payee']) || !isset($data['amount'])) {
            return ['error' => 'Invalid data'];
        }

        $payer_id = $data['payer']['id'];

        if (!$this->userService->canMakeTransactions($payer_id)) {
            return ['error' => 'User cannot make transactions'];
        }

        $payer_wallet = $this->walletService->getUserDefaultWallet($payer_id);
        if (isset($data['payer']['wallet_id'])) {
            $payer_wallet = $this->walletService->getWallet($data['payer']['wallet_id']);
        }

        if ($payer_wallet->balance < $data['amount']) {
            return ['error' => 'Insufficient funds'];
        }

        $payee_wallet = $this->walletService->getUserDefaultWallet($data['payee']['id']);
        if (isset($data['payee']['wallet_id'])) {
            $payee_wallet = $this->walletService->getWallet($data['payee']['wallet_id']);
        }

        $transaction = $this->transactionRepository->createTransaction([
            'payer_wallet_id' => $payer_wallet->id,
            'payee_wallet_id' => $payee_wallet->id,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
        ]);

        if (!$transaction) {
            return ['error' => 'Transaction could not be created'];
        }

        $this->startTransaction();

        try {
            $this->walletService->updateWalletBalance($payer_wallet->id, $payer_wallet->balance - $data['amount']);
            $this->walletService->updateWalletBalance($payee_wallet->id, $payee_wallet->balance + $data['amount']);

            $authorized = $this->authorizeTransaction($transaction, $data);

            if (!$authorized) {
                throw new \Exception('Transaction not authorized');
            }

            $transaction = $this->updateTransactionStatus($transaction->id, 'completed');

            if (!$transaction) {
                throw new \Exception('Transaction could not be completed');
            }

            $this->notificationService->createNotifications($transaction);

            $this->commitTransaction();

            return $transaction;
        } catch (\Exception $e) {
            $this->rollbackTransaction();

            if (isset($transaction)) {
                $transaction = $this->updateTransactionStatus($transaction->id, 'failed');

                $this->notificationService->createFailedNotifications($transaction);
            }

            report($e);
        }

        return ['error' => 'Transaction could not be completed'];
    }

    /**
     * Get all transactions from a user.
     *
     * @param int $user_id
     * @return Transaction[]
     */
    public function getTransactions($user_id)
    {
        return $this->transactionRepository->getTransactions($user_id);
    }

    /**
     * Get a transaction by its ID.
     *
     * @param int $transaction_id
     * @return Transaction
     */
    public function getTransaction($transaction_id)
    {
        return $this->transactionRepository->getTransaction($transaction_id);
    }

    /**
     * Update a transaction status.
     *
     * @param int $transaction_id
     * @param string $status
     * @return Transaction
     */
    public function updateTransactionStatus($transaction_id, $status)
    {
        return $this->transactionRepository->updateTransaction($transaction_id, [
            'status' => $status,
        ]);
    }

    /**
     * Authorize a transaction.
     *
     * @param Transaction $transaction
     * @param array $data
     * @return bool
     */
    private function authorizeTransaction($transaction, $data)
    {
        return $this->authorizationService->authorizeTransaction($transaction, $data);
    }
}
