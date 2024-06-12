<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\TransactionServiceContract;
use App\Http\Requests\Api\TransactionCreateRequest;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    public function __construct(
        private TransactionServiceContract $transactionService,
        private AuthServiceContract $authService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = $this->transactionService->getTransactions($this->authService->getAuthUserId());

        return $this->jsonReponse($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionCreateRequest $request)
    {
        $validated = $request->validated();

        if (!isset($validated['payer'])) {
            $validated['payer'] = [];
        }

        $validated['payer']['id'] = $this->authService->getAuthUserId();

        $result = $this->transactionService->createTransaction($validated);

        return $this->jsonReponse($result, 201);
    }
}
