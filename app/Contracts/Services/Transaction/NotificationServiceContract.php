<?php

namespace App\Contracts\Services\Transaction;

interface NotificationServiceContract
{
    public function notifyPayee($transaction);
    public function notifyPayer($transaction);
    public function notifyPayerFailed($transaction);
}
