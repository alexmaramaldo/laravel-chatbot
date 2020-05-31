<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * TransactionService Class with the main logic to transactions
 */
class TransactionService
{
    private $transactionRepository;

    /**
     * Create a new TransactionService instance
     *
     * @param TransactionRepository $transactionRepo Dependency injection
     *
     * @return void
     */
    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepository = $transactionRepo;
    }

    /**
     * Deposit a value on logged User
     *
     * @param array $parameters Params with value
     *
     * @throws Exception
     *
     * @return bool
     */
    public function deposit(array $parameters): bool
    {
        try {

            $user = Auth::user();
            $data = [
                'value' => $parameters['value'],
                'type' => 'deposit'
            ];
            $user->transactions()->create($data);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Withdraw a value on logged User
     *
     * @param array $parameters Params with value
     *
     * @throws Exception
     *
     * @return bool
     */
    public function withdraw(array $parameters)
    {
        try {
            $user = Auth::user();
            if ($user->myBalance() < $parameters['value']) {
                throw new Exception('Insufficient funds', 400);
            }

            $data = [
                'value' => $parameters['value'],
                'type' => 'withdraw'
            ];
            $user->transactions()->create($data);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
