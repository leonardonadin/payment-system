<?php

namespace App\Services;

use App\Contracts\Services\NotifyServiceContract;

class NotifyService implements NotifyServiceContract
{
    public function sendNotification($notification)
    {
        $http = new \GuzzleHttp\Client;

        try {
            if (config('services.notification.token')) {
                $http['headers']['Authorization'] = 'Bearer ' . config('services.notification.token');
            }

            $response = $http->post(config('services.notification.url'), [
                'json' => $notification
            ]);

            if ($response->getStatusCode() == 200) {
                return true;
            }
        } catch (\Exception $e) {
            report($e);
        }

        return false;
    }
}
