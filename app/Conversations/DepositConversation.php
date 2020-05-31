<?php

namespace App\Conversations;

use App\Services\AccountService;
use App\Services\CurrencyAPIService;
use App\Services\TransactionService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Auth;

class DepositConversation extends Conversation
{
    protected $value;
    protected $currency;

    protected $transactionService;
    protected $currencyAPIService;

    /**
     * Create a new DepositConversation instance
     *
     * @param TransactionService $transactionService Dependency injection
     * @param CurrencyAPIService $currencyAPIService Dependency injection
     *
     * @return void
     */
    public function __construct(TransactionService $transactionService, CurrencyAPIService $currencyAPIService)
    {
        $this->transactionService = $transactionService;
        $this->currencyAPIService = $currencyAPIService;
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
                return $this->askCurrency();
            } else {
                $this->say("You need inform a numeric value ex: <b>1123.34</b>.<br />Try again...");
                return $this->askValue();
            }
        });
    }

    /**
     * Apply the the action to get the currency
     *
     */
    public function askCurrency()
    {
        $this->ask('Type \'skip\' to use USD or inform the new currency', function (Answer $answer) {
            // Save result
            $this->currency = strtoupper($answer->getText());

            if ($this->currency != 'SKIP' && $this->currency != 'USD') {
                if (!$this->currencyAPIService->checkIfCurrencyExist($this->currency)) {
                    $this->say("You need select an valid currency, try again");
                    return $this->askCurrency();
                }
            }

            $value_in_usd = $this->value;

            if ($this->currency != 'SKIP' && $this->currency != 'USD') {
                $value_in_usd = $this->currencyAPIService->convertAmount($this->currency, 'USD', $this->value);
            }

            $this->transactionService->deposit(['value' => $value_in_usd]);
            return $this->say("Depositing successfully, check your balance with the command <b> my balance </b>.");
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
