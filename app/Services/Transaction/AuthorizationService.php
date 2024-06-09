<?php

namespace App\Services\Transaction;

use App\Contracts\Services\Transaction\AuthorizationServiceContract;

class AuthorizationService implements AuthorizationServiceContract
{
    public function authorizeTransaction($transaction, $data)
    {
        $http = new \GuzzleHttp\Client;

        try {
            if (config('services.authorization.token')) {
                $http['headers']['Authorization'] = 'Bearer ' . config('services.authorization.token');
            }

            $response = $http->post(config('services.authorization.url'), [
                'json' => [
                    'transaction_id' => $transaction->id,
                ],
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
