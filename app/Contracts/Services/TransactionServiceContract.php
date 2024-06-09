<?php

namespace App\Contracts\Services;

interface TransactionServiceContract
{
    public function createTransaction($data);
    public function getTransactions($user_id);
    public function getTransaction($transaction_id);
}
