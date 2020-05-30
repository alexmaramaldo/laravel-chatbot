<?php

namespace App\Conversations;

use App\Services\AccountService;
use App\Services\TransactionService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Exception;
use Illuminate\Support\Facades\Auth;

class WithdrawConversation extends Conversation
{
    protected $value;

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function askValue()
    {
        $this->ask('What amount you want to <b>withdraw</b>?', function (Answer $answer) {
            // Save result
            $this->value = $answer->getText();

            try {
                $this->transactionService->withdraw(['value' => $this->value]);
                $this->say("Withdrawing successfully, check your balance with the command <b> my balance </b>.");
            } catch (Exception $e) {
                if ($e->getCode() == 400) {
                    $this->say("We had an error: " . $e->getMessage());
                } else {
                    $this->say("We had an unknow error: " . $e->getMessage());
                }
            }
        });
    }



    public function run()
    {
        // This will be called immediately
        $this->askValue();
    }
}
