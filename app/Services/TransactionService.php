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

            $this->validateValue($parameters['value'], 'deposit');

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

            $this->validateValue($parameters['value'], 'withdraw', $user->myBalance());

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


    /**
     * validateValue, check if the value is > 0
     *
     * @param [type] $value
     * @return bool
     */
    public function validateValue($value, $type, $balance = 0): bool
    {
        if ($value < 0) {
            throw new Exception("You need inform a value > 0", 400);
        }

        if ($type == 'withdraw') {
            if ($balance < $value) {
                throw new Exception('Insufficient funds', 400);
            }
        }

        return true;
    }
}
