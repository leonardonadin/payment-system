<?php

namespace App\Jobs\Notification\Transaction;

use App\Contracts\Services\Transaction\NotificationServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSuccessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $transaction)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationServiceContract $notificationService): void
    {
        $notificationService->notifyPayee($this->transaction);
        $notificationService->notifyPayer($this->transaction);
    }
}
