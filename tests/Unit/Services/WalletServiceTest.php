<?php

namespace Tests\Unit\Services;

use App\Contracts\Repositories\WalletRepositoryContract;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $walletService;
    protected $walletRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = $this->createMock(WalletRepositoryContract::class);
        $this->walletService = new WalletService($this->walletRepository);
    }

    public function test_getUserDefaultWallet_returns_wallet()
    {
        $this->walletRepository->method('getUserDefaultWallet')->willReturn(new Wallet());
        $result = $this->walletService->getUserDefaultWallet(1);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_getUserWallets_returns_wallets()
    {
        $this->walletRepository->method('getUserWallets')->willReturn(collect([new Wallet(), new Wallet()]));
        $result = $this->walletService->getUserWallets(1);
        $this->assertCount(2, $result);
    }

    public function test_getWallet_returns_wallet()
    {
        $this->walletRepository->method('getWallet')->willReturn(new Wallet());
        $result = $this->walletService->getWallet(1);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_createWallet_returns_wallet()
    {
        $this->walletRepository->method('createWallet')->willReturn(new Wallet());
        $result = $this->walletService->createWallet(['balance' => 100]);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_updateWalletBalance_returns_wallet()
    {
        $this->walletRepository->method('updateWallet')->willReturn(new Wallet());
        $result = $this->walletService->updateWalletBalance(1, 200);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_adjustWalletBalance_returns_wallet_when_in()
    {
        $wallet = new Wallet();
        $wallet->balance = 100;
        $this->walletRepository->method('getWallet')->willReturn($wallet);
        $this->walletRepository->method('updateWallet')->willReturn($wallet);
        $result = $this->walletService->adjustWalletBalance(1, 'in', 50);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_adjustWalletBalance_returns_wallet_when_out()
    {
        $wallet = new Wallet();
        $wallet->balance = 100;
        $this->walletRepository->method('getWallet')->willReturn($wallet);
        $this->walletRepository->method('updateWallet')->willReturn($wallet);
        $result = $this->walletService->adjustWalletBalance(1, 'out', 50);
        $this->assertInstanceOf(Wallet::class, $result);
    }

    public function test_adjustWalletBalance_returns_false_when_out_and_insufficient_balance()
    {
        $wallet = new Wallet();
        $wallet->balance = 40;
        $this->walletRepository->method('getWallet')->willReturn($wallet);
        $result = $this->walletService->adjustWalletBalance(1, 'out', 50);
        $this->assertFalse($result);
    }
}
