<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryContract;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryContract
{
    public function getTransactions($user_id)
    {
        return Transaction::where('user_id', $user_id)->get();
    }

    public function getTransaction($transaction_id)
    {
        return Transaction::find($transaction_id);
    }

    public function createTransaction($data)
    {
        $transaction = new Transaction();
        $transaction->payer_wallet_id = $data['payer_wallet_id'];
        $transaction->payee_wallet_id = $data['payee_wallet_id'];
        $transaction->amount = $data['amount'];
        $transaction->status = $data['status'];
        if (isset($data['description'])) {
            $transaction->description = $data['description'];
        }
        $transaction->save();

        return $transaction;
    }

    public function updateTransaction($transaction_id, $data)
    {
        $transaction = Transaction::find($transaction_id);
        if (isset($data['status'])) {
            $transaction->status = $data['status'];
        }
        $transaction->save();

        return $transaction;
    }
}
