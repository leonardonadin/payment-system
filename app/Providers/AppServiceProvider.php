<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        // Repositories
        \App\Contracts\Repositories\UserRepositoryContract::class => \App\Repositories\UserRepository::class,
        \App\Contracts\Repositories\AuthRepositoryContract::class => \App\Repositories\AuthRepository::class,
        \App\Contracts\Repositories\WalletRepositoryContract::class => \App\Repositories\WalletRepository::class,
        \App\Contracts\Repositories\TransactionRepositoryContract::class => \App\Repositories\TransactionRepository::class,

        // Services
        \App\Contracts\Services\UserServiceContract::class => \App\Services\UserService::class,
        \App\Contracts\Services\AuthServiceContract::class => \App\Services\AuthService::class,
        \App\Contracts\Services\NotifyServiceContract::class => \App\Services\NotifyService::class,
        \App\Contracts\Services\WalletServiceContract::class => \App\Services\WalletService::class,
        \App\Contracts\Services\TransactionServiceContract::class => \App\Services\TransactionService::class,
        \App\Contracts\Services\Transaction\AuthorizationServiceContract::class => \App\Services\Transaction\AuthorizationService::class,
        \App\Contracts\Services\Transaction\NotificationServiceContract::class => \App\Services\Transaction\NotificationService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
