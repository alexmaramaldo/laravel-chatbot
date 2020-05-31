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

    /**
     * Create a new DepositConversation instance
     *
     * @param TransactionService $transactionService Dependency injection from repository layer
     *
     * @return void
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Apply the the action to get the value
     *
     */
    public function askValue()
    {
        $this->ask('What amount you want to deposit?', function (Answer $answer) {
            // Save result
            $this->value = $answer->getText();

            if (is_numeric($this->value)) {
                $this->transactionService->deposit(['value' => $this->value]);
                $this->say("Depositing successfully, check your balance with the command <b> my balance </b>.");
            } else {
                $this->say("You need inform a numeric value ex: <b>1123.34</b>.<br />Try again...");
                return $this->askValue();
            }
        });
    }

    /**
     * Main method to run this Conversation
     *
     */
    public function run()
    {
        // This will be called immediately
        $this->askValue();
    }
}
