<?php

namespace App\Services\Transaction;

use App\Contracts\Services\NotifyServiceContract;
use App\Contracts\Services\Transaction\NotificationServiceContract as TransactionNotificationServiceContract;
use App\Contracts\Services\WalletServiceContract;

class NotificationService implements TransactionNotificationServiceContract
{
    public function __construct(
        private NotifyServiceContract $notifyService,
        private WalletServiceContract $walletService
    )
    {
    }

    /**
     * Notify the payee about the transaction.
     *
     * @param object $transaction
     * @return void
     */
    public function notifyPayee($transaction)
    {
        $payee_wallet = $this->walletService->getWallet($transaction->payee_wallet_id);

        $notification = [
            'type' => 'transaction',
            'user_id' => $payee_wallet->user_id,
            'title' => 'New transaction',
            'message' => 'You have received a new transaction',
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->id
        ];

        return $this->sendNotification($notification);
    }

    /**
     * Notify the payer about the transaction.
     *
     * @param object $transaction
     * @return void
     */
    public function notifyPayer($transaction)
    {
        $payer_wallet = $this->walletService->getWallet($transaction->payer_wallet_id);

        $notification = [
            'type' => 'transaction',
            'user_id' => $payer_wallet->user_id,
            'title' => 'Transaction completed',
            'message' => 'Your transaction has been completed successfully',
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->id
        ];

        return $this->sendNotification($notification);
    }

    /**
     * Notify the payer about the failed transaction.
     *
     * @param object $transaction
     * @return void
     */
    public function notifyPayerFailed($transaction)
    {
        $payer_wallet = $this->walletService->getWallet($transaction->payer_wallet_id);

        $notification = [
            'type' => 'transaction',
            'user_id' => $payer_wallet->user_id,
            'title' => 'Transaction failed',
            'message' => 'Your transaction has failed',
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->id
        ];

        return $this->sendNotification($notification);
    }

    private function sendNotification($notification)
    {
        $this->notifyService->sendNotification($notification);
    }
}
