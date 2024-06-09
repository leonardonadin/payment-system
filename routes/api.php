<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'store']);
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LogoutController::class, 'store']);

    Route::controller(App\Http\Controllers\Api\UserController::class)->group(function() {
        Route::get('/users', 'index');
        Route::get('/user', 'show');
    });

    Route::controller(App\Http\Controllers\Api\WalletController::class)->prefix('/wallets')->group(function() {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{wallet_id}', 'show');
        Route::put('/{wallet_id}', 'update');
        Route::delete('/{wallet_id}', 'destroy');
    });

    Route::controller(App\Http\Controllers\Api\TransactionController::class)->prefix('/transactions')->group(function() {
        Route::post('/', 'store');
    });
});
