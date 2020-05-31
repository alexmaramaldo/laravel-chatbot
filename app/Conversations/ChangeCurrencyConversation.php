<?php

namespace App\Conversations;

use App\Services\AccountService;
use App\Services\CurrencyAPIService;
use App\Services\TransactionService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Exception;
use Illuminate\Support\Facades\Auth;

class ChangeCurrencyConversation extends Conversation
{
    protected $currency;

    protected $currencyAPIService;
    protected $accountService;

    /**
     * Create a new ChangeCurrencyConversation instance
     *
     * @param CurrencyAPIService $currencyAPIService Dependency injection from repository layer
     * @param AccountService $accountService Dependency injection from repository layer
     *
     * @return void
     */
    public function __construct(CurrencyAPIService $currencyAPIService, AccountService $accountService)
    {
        $this->currencyAPIService = $currencyAPIService;
        $this->accountService = $accountService;
    }


    /**
     * Apply the the action to get the value
     *
     */
    public function askCurrency()
    {
        $message = "<b>Currency List:</b><br />";
        $currencyList = array();
        foreach ($this->currencyAPIService->currencyList() as $currency) {
            $currencyList[] = '<b>' . $currency['currency'] . '</b> - ' . $currency['description'];
        }

        $message .= implode("<br />", $currencyList);

        $this->say("Choose an option bellow!");

        $this->ask($message, function (Answer $answer) {
            // Save result
            $this->currency = strtoupper($answer->getText());

            if (!$this->currencyAPIService->checkIfCurrencyExist($this->currency)) {
                $this->say("You need select an valid currency, try again");
                return $this->askCurrency();
            }

            if ($this->currency == Auth::user()->currency) {
                return $this->say("You already use this currency");
            }
            try {

                $this->accountService->updateCurrency(Auth::user()->id, $this->currency);
                $this->say("Currency updated successfully");
            } catch (Exception $e) {
                $this->say("Ooops, we had an error: " . $e->getMessage());
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
        $this->askCurrency();
    }
}
