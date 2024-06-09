<?php

namespace App\Contracts\Services\Transaction;

interface AuthorizationServiceContract
{
    public function authorizeTransaction($transaction, $data);
}
