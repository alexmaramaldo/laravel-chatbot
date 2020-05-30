<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransactionService
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepository = $transactionRepo;
    }

    public function deposit(array $parameters)
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
            throw new Exception($e);
        }
    }

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
