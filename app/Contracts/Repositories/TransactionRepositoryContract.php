<?php

namespace App\Contracts\Repositories;

interface TransactionRepositoryContract
{
    public function getTransactions($user_id);
    public function getTransaction($transaction_id);
    public function createTransaction($data);
    public function updateTransaction($transaction_id, $data);
}
