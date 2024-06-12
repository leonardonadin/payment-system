<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\TransactionServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionCreateRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
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

        return response()->json($transactions);
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

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json($result, 201);
    }
}
