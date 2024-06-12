<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WalletCreateRequest;
use App\Http\Requests\Api\WalletUpdateRequest;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private WalletServiceContract $walletService,
        private AuthServiceContract $authService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = $this->walletService->getUserWallets($this->authService->getAuthUserId());

        return response()->json($wallets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WalletCreateRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = $this->authService->getAuthUserId();

        $wallet = $this->walletService->createWallet($validated);

        if (!$wallet) {
            return response()->json(['error' => 'Error creating wallet'], 400);
        }

        return response()->json($wallet, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $wallet_id)
    {
        $wallet = $this->walletService->getWallet($wallet_id);

        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found'], 404);
        }

        return response()->json($wallet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WalletUpdateRequest $request, string $wallet_id)
    {
        $result = $this->walletService->adjustWalletBalance($wallet_id, $request->type, $request->amount);

        if (!$result) {
            return response()->json(['error' => 'Error updating wallet'], 400);
        }

        return response()->json(['message' => 'Wallet updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $wallet_id)
    {
        $result = $this->walletService->deleteWallet(
            $this->authService->getAuthUserId(),
            $wallet_id
        );

        if (!$result) {
            return response()->json(['error' => 'Error deleting wallet'], 400);
        }

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json(['message' => 'Wallet deleted successfully']);
    }
}
