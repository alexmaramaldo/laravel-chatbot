<?php

namespace App\Conversations;

use App\Services\AccountService;
use App\Services\TransactionService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Auth;

class DepositConversation extends Conversation
{
    protected $value;

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function askValue()
    {
        $this->ask('What amount you want to deposit?', function (Answer $answer) {
            // Save result
            $this->value = $answer->getText();

            $this->transactionService->deposit(['value' => $this->value]);

            $this->say("Depositing successfully, check your balance with the command <b> my balance </b>.");
        });
    }



    public function run()
    {
        // This will be called immediately
        $this->askValue();
    }
}
