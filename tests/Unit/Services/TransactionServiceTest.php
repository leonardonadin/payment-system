<?php

namespace Tests\Unit\Services;

use App\Contracts\Repositories\TransactionRepositoryContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $transactionRepository;
    protected $walletService;
    protected $userService;
    protected $transactionService;

    protected $payer_user;
    protected $payer_wallet;
    protected $payee_user;
    protected $payee_wallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = Mockery::mock(TransactionRepositoryContract::class);
        $this->walletService = Mockery::mock(WalletServiceContract::class);
        $this->userService = Mockery::mock(UserServiceContract::class);

        $this->transactionService = new TransactionService(
            $this->transactionRepository,
            $this->walletService,
            $this->userService
        );
    }

    public function test_get_transactions()
    {
        $this->transactionRepository->shouldReceive('getTransactions')->andReturn([
            (object)['id' => 1, 'status' => 'completed'],
            (object)['id' => 2, 'status' => 'pending']
        ]);

        $transactions = $this->transactionService->getTransactions(1);

        $this->assertCount(2, $transactions);
    }

    public function test_get_transaction()
    {
        $this->transactionRepository->shouldReceive('getTransaction')->andReturn((object)['id' => 1, 'status' => 'completed']);

        $transaction = $this->transactionService->getTransaction(1);

        $this->assertEquals(1, $transaction->id);
        $this->assertEquals('completed', $transaction->status);
    }

    public function test_update_transaction_status()
    {
        $this->transactionRepository->shouldReceive('updateTransaction')->andReturn((object)['id' => 1, 'status' => 'completed']);

        $transaction = $this->transactionService->updateTransactionStatus(1, 'completed');

        $this->assertEquals(1, $transaction->id);
        $this->assertEquals('completed', $transaction->status);
    }
}
