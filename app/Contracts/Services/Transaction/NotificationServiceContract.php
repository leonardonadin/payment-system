<?php

namespace App\Contracts\Services\Transaction;

interface NotificationServiceContract
{
    public function createNotifications($transaction);
    public function createFailedNotifications($transaction);
    public function notifyPayee($transaction);
    public function notifyPayer($transaction);
    public function notifyPayerFailed($transaction);
}
