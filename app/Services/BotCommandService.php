<?php

namespace App\Services;

use App\Conversations\ChangeCurrencyConversation;
use App\Conversations\DepositConversation;
use App\Conversations\LoginConversation;
use App\Conversations\LogoutConversation;
use App\Conversations\RegisterConversation;
use App\Conversations\WithdrawConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use Exception;
use Illuminate\Support\Facades\Auth;

class BotCommandService
{
    private $registerConversation;
    private $loginConversation;
    private $logoutConversation;
    private $depositConversation;
    private $withdrawConversation;
    private $changeCurrencyConversation;

    public function __construct(
        RegisterConversation $registerConversation,
        LoginConversation $loginConversation,
        LogoutConversation $logoutConversation,
        DepositConversation $depositConversation,
        WithdrawConversation $withdrawConversation,
        ChangeCurrencyConversation  $changeCurrencyConversation
    ) {
        $this->registerConversation = $registerConversation;
        $this->loginConversation = $loginConversation;
        $this->logoutConversation = $logoutConversation;
        $this->depositConversation = $depositConversation;
        $this->withdrawConversation = $withdrawConversation;
        $this->changeCurrencyConversation = $changeCurrencyConversation;
    }

    public function listenCommand($botman, $message)
    {
        switch (strtolower(trim($message))) {
            case 'help':
                $this->helpCommand($botman);
                break;
            case 'create new account':
                $botman->startConversation($this->registerConversation);
                break;
            case 'login':
                $botman->startConversation($this->loginConversation);
                break;
            case 'logout':
                $botman->startConversation($this->logoutConversation);
                break;
            case 'my balance':
                $this->isLogged($botman) ? $this->myBalanceCommand($botman) : '';
                break;
            case 'deposit':
                $this->isLogged($botman) ? $botman->startConversation($this->depositConversation) : '';
                break;
            case 'withdraw':
                $this->isLogged($botman) ? $botman->startConversation($this->withdrawConversation) : '';
                break;
            case 'my currency':
                $this->isLogged($botman) ? $this->myCurrency($botman) : '';
                break;
            case 'list currencies':
                $this->isLogged($botman) ? $this->listCurrencies($botman) : '';
                break;
            case 'change my currency':
                $this->isLogged($botman) ? $botman->startConversation($this->changeCurrencyConversation) : '';
                break;
            default:
                $answer = "
                    Hey, I don't understand, maybe I can show to you all possible commands<br />
                    Write <b>help</b> to see all commands..
                ";
                $botman->reply($answer);
        }
    }

    public function helpCommand($botman)
    {
        $message = "
            <b>help</b> to show all commands<br />
            <b>create new account</b> to register an account on ChatBank<br />
            <b>login</b> to enter on ChatBank<br />
            <b>logout</b> to leave the ChatBank<br />
            <b>my balance</b> to show your current balance<br />
            <b>deposit</b> to deposit a money on ChatBank<br />
            <b>withdraw</b> to withdraw a money on ChatBank<br />
            <b>my currency</b> to show your current set currency<br />
            <b>list currencies</b> to list all allowed currencies on ChatBank<br />
            <b>change my currency</b> to select a new currency on ChatBank<br />

        ";
        $botman->reply($message);
    }

    public function myBalanceCommand($botman)
    {
        $botman->reply('Your balance is ' . Auth::user()->currency . ' ' . number_format(Auth::user()->myBalance(), 2));
    }

    public function myCurrency($botman)
    {
        $botman->reply('Your currency is ' . Auth::user()->currency);
    }

    public function listCurrencies($botman)
    {
        try {
            $currencyAPIService = new CurrencyAPIService();
            $message = "All currencies<br />";
            $currencyList = array();
            foreach ($currencyAPIService->currencyList() as $currency) {
                $currencyList[] = '<b>' . $currency['currency'] . '</b> - ' . $currency['description'];
            }

            $message .= implode("<br />", $currencyList);

            $botman->reply($message);
        } catch (Exception $e) {
            if ($e->getCode() == 400) {
                $botman->reply("Oooops, we had an error: " . $e->getMessage());
            } else {
                $botman->reply("Oooops, we had an error!!!");
            }
        }
    }

    public function isLogged($botman)
    {
        if (!Auth::check()) {
            $botman->reply("Hey, I can't to do it, you need are logged on ChatBank to execute this command! Type <b>login</b> or <b>help</b> to see all commands!!!!");
            return false;
        } else {
            return true;
        }
    }
}
