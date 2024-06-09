<?php

namespace App\Services;

use App\Contracts\Repositories\TransactionRepositoryContract;
use App\Contracts\Services\Transaction\AuthorizationServiceContract;
use App\Contracts\Services\TransactionServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Jobs\Notification\Transaction\SendFailJob as SendFailNotificationJob;
use App\Jobs\Notification\Transaction\SendSuccessJob as SendSuccessNotificationJob;
use App\Trait\Services\ReversibleActionsTrait;

class TransactionService implements TransactionServiceContract
{
    use ReversibleActionsTrait;

    public function __construct(
        private TransactionRepositoryContract $transactionRepository,
        private WalletServiceContract $walletService,
        private UserServiceContract $userService
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
        $payer_wallet = $this->walletService->getDefaultWallet(auth()->user()->id);
        if (isset($data['payer']['wallet_id'])) {
            $payer_wallet = $this->walletService->getWallet($data['payer']['wallet_id']);
        }

        if (!$this->userService->canMakeTransactions($payer_wallet->user_id)) {
            return ['error' => 'User cannot make transactions'];
        }

        $payee_wallet = $this->walletService->getDefaultWallet($data['payee']['id']);
        if (isset($data['payee']['wallet_id'])) {
            $payee_wallet = $this->walletService->getWallet($data['payee']['wallet_id']);
        }

        $data['payer_wallet_id'] = $payer_wallet->id;
        $data['payee_wallet_id'] = $payee_wallet->id;

        if ($payer_wallet->balance < $data['amount']) {
            return ['error' => 'Insufficient funds'];
        }

        $transaction = $this->transactionRepository->createTransaction([
            'payer_wallet_id' => $data['payer_wallet_id'],
            'payee_wallet_id' => $data['payee_wallet_id'],
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

            $this->sendNotifications($transaction);

            $this->commitTransaction();

            return $transaction;
        } catch (\Exception $e) {
            $this->rollbackTransaction();

            if (isset($transaction)) {
                $transaction = $this->updateTransactionStatus($transaction->id, 'failed');

                SendFailNotificationJob::dispatch($transaction);
            }

            report($e);
        }

        return ['error' => 'Transaction could not be completed'];
    }

    public function getTransactions($user_id)
    {
        return $this->transactionRepository->getTransactions($user_id);
    }

    public function getTransaction($transaction_id)
    {
        return $this->transactionRepository->getTransaction($transaction_id);
    }

    public function updateTransactionStatus($transaction_id, $status)
    {
        return $this->transactionRepository->updateTransaction($transaction_id, [
            'status' => $status,
        ]);
    }

    private function authorizeTransaction($transaction, $data)
    {
        $authorizationService = app()->makeWith(AuthorizationServiceContract::class);
        return $authorizationService->authorizeTransaction($transaction, $data);
    }

    private function sendNotifications($transaction)
    {
        SendSuccessNotificationJob::dispatch($transaction);
    }
}
