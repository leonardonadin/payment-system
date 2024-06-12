<?php

namespace Tests\Unit\Services;

use App\Contracts\Repositories\TransactionRepositoryContract;
use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\Transaction\AuthorizationServiceContract;
use App\Contracts\Services\Transaction\NotificationServiceContract;
use App\Contracts\Services\TransactionServiceContract;
use App\Contracts\Services\UserServiceContract;
use App\Contracts\Services\WalletServiceContract;
use App\Jobs\Notification\Transaction\SendFailJob;
use App\Jobs\Notification\Transaction\SendSuccessJob;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $transactionService;
    protected $transactionRepository;
    protected $walletService;
    protected $userService;
    protected $authService;
    protected $notificationService;
    protected $authorizationService;

    protected $payerUser;
    protected $payerWallet;
    protected $payeeUser;
    protected $payeeWallet;

    public function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = $this->createMock(TransactionRepositoryContract::class);
        $this->walletService = $this->createMock(WalletServiceContract::class);
        $this->userService = $this->createMock(UserServiceContract::class);
        $this->authService = $this->createMock(AuthServiceContract::class);
        $this->notificationService = $this->createMock(NotificationServiceContract::class);
        $this->authorizationService = $this->createMock(AuthorizationServiceContract::class);

        $this->transactionService = new TransactionService(
            $this->transactionRepository,
            $this->walletService,
            $this->userService,
            $this->authService,
            $this->authorizationService,
            $this->notificationService
        );

        DB::shouldReceive('beginTransaction');
        DB::shouldReceive('commit');
        DB::shouldReceive('rollBack');

        $this->payerUser = User::factory()->create();
        $this->payerWallet = Wallet::factory()->create(['user_id' => $this->payerUser->id, 'balance' => 100]);
        $this->actingAs($this->payerUser);

        $this->payeeUser = User::factory()->create();
        $this->payeeWallet = Wallet::factory()->create(['user_id' => $this->payeeUser->id, 'balance' => 100]);
    }

    public function test_createTransaction_returns_error_when_user_cannot_make_transactions()
    {
        $this->userService->method('canMakeTransactions')->willReturn(false);

        $result = $this->transactionService->createTransaction([
            'payer' => ['id' => $this->payerUser->id],
            'payee' => ['id' => $this->payeeUser->id],
            'amount' => 100
        ]);

        $this->assertEquals(['error' => 'User cannot make transactions'], $result);
    }

    public function test_createTransaction_returns_error_when_insufficient_funds()
    {
        $this->payerWallet->balance = 50;
        $this->payerWallet->save();

        $this->walletService->method('getUserDefaultWallet')->willReturn($this->payerWallet);
        $this->userService->method('canMakeTransactions')->willReturn(true);

        $result = $this->transactionService->createTransaction([
            'payer' => ['id' => $this->payerUser->id],
            'payee' => ['id' => $this->payeeUser->id],
            'amount' => 100
        ]);

        $this->assertEquals(['error' => 'Insufficient funds'], $result);
    }

    public function test_createTransaction_returns_error_when_transaction_could_not_be_created()
    {
        $this->walletService->method('getUserDefaultWallet')->willReturn($this->payerWallet);
        $this->userService->method('canMakeTransactions')->willReturn(true);

        $result = $this->transactionService->createTransaction([
            'payer' => ['id' => $this->payerUser->id],
            'payee' => ['id' => $this->payeeUser->id],
            'amount' => 10
        ]);

        $this->assertEquals(['error' => 'Transaction could not be created'], $result);
    }

    public function test_createTransaction_returns_transaction_when_successful()
    {
        $this->userService->method('canMakeTransactions')->willReturn(true);
        $this->walletService->method('getUserDefaultWallet')->willReturn($this->payerWallet);
        $this->transactionRepository->method('createTransaction')->willReturn((object) ['id' => 1]);
        $this->transactionRepository->method('updateTransaction')->willReturn((object) ['id' => 1]);
        $this->authorizationService->method('authorizeTransaction')->willReturn(true);
        $this->notificationService->method('createNotifications');

        $result = $this->transactionService->createTransaction([
            'payer' => ['id' => $this->payerUser->id],
            'payee' => ['id' => $this->payeeUser->id],
            'amount' => 100
        ]);

        $this->assertEquals(1, $result->id);
    }

    public function test_getTransactions_returns_transactions()
    {
        $this->transactionRepository->method('getTransactions')->willReturn([(object) ['id' => 1]]);

        $result = $this->transactionService->getTransactions(1);

        $this->assertEquals(1, $result[0]->id);
    }

    public function test_getTransaction_returns_transaction()
    {
        $this->transactionRepository->method('getTransaction')->willReturn((object) ['id' => 1]);

        $result = $this->transactionService->getTransaction(1);

        $this->assertEquals(1, $result->id);
    }

    public function test_updateTransactionStatus_returns_updated_transaction()
    {
        $this->transactionRepository->method('updateTransaction')->willReturn((object) ['status' => 'completed']);

        $result = $this->transactionService->updateTransactionStatus(1, 'completed');

        $this->assertEquals('completed', $result->status);
    }
}
