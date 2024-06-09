<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\TransactionServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionCreateRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private TransactionServiceContract $transactionService)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionCreateRequest $request)
    {
        $result = $this->transactionService->createTransaction($request->validated());

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json($result, 201);
    }
}
