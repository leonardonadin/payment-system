<?php
namespace Tests\Unit\Services;

use App\Contracts\Repositories\WalletRepositoryContract;
use App\Services\WalletService;
use Tests\Unit\UnitTestCase;

class WalletServiceTest extends UnitTestCase
{
    protected $walletRepository;
    protected $walletService;

    public function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = $this->createMock(WalletRepositoryContract::class);
        $this->walletService = new WalletService($this->walletRepository);
    }

    public function testGetDefaultWallet()
    {
        $wallet = 'wallet1';
        $this->walletRepository->method('getDefaultWallet')->willReturn($wallet);
        $this->assertEquals($wallet, $this->walletService->getDefaultWallet(1));
    }

    public function testGetWallets()
    {
        $wallets = ['wallet1', 'wallet2'];
        $this->walletRepository->method('getWallets')->willReturn($wallets);
        $this->assertEquals($wallets, $this->walletService->getWallets());
    }

    public function testGetWallet()
    {
        $wallet = 'wallet1';
        $this->walletRepository->method('getWallet')->willReturn($wallet);
        $this->assertEquals($wallet, $this->walletService->getWallet(1));
    }

    public function testCreateWallet()
    {
        $wallet = 'wallet1';
        $this->walletRepository->method('createWallet')->willReturn($wallet);
        $this->assertEquals($wallet, $this->walletService->createWallet(['name' => 'Wallet 1']));
    }

    public function testUpdateWalletBalance()
    {
        $wallet = 'wallet1';
        $this->walletRepository->method('updateWallet')->willReturn($wallet);
        $this->assertEquals($wallet, $this->walletService->updateWalletBalance(1, 100));
    }

    public function testAdjustWalletBalance()
    {
        $wallet = (object) ['balance' => 100];
        $this->walletRepository->method('getWallet')->willReturn($wallet);
        $this->walletRepository->method('updateWallet')->willReturn($wallet);
        $this->assertEquals($wallet, $this->walletService->adjustWalletBalance(1, 'in', 50));
        $this->assertEquals($wallet, $this->walletService->adjustWalletBalance(1, 'out', 50));
    }

    public function testDeleteWallet()
    {
        $wallets = ['wallet1', 'wallet2'];
        $this->walletRepository->method('getWallets')->willReturn($wallets);
        $this->walletRepository->method('deleteWallet')->willReturn(true);
        $this->assertTrue($this->walletService->deleteWallet(1));
    }
}
