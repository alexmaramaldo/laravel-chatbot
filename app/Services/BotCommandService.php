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
use Illuminate\Support\Facades\Auth;

class BotCommandService
{
    private $registerConversation;
    private $loginConversation;
    private $logoutConversation;
    private $depositConversation;
    private $withdrawConversation;

    public function __construct(
        RegisterConversation $registerConversation,
        LoginConversation $loginConversation,
        LogoutConversation $logoutConversation,
        DepositConversation $depositConversation,
        WithdrawConversation $withdrawConversation
    ) {
        $this->registerConversation = $registerConversation;
        $this->loginConversation = $loginConversation;
        $this->logoutConversation = $logoutConversation;
        $this->depositConversation = $depositConversation;
        $this->withdrawConversation = $withdrawConversation;
    }

    public function listenCommand($botman, $message)
    {
        switch (strtolower($message)) {
            case 'hi':
                $this->askName($botman);
                break;
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
                $this->myBalanceCommand($botman);
                break;
            case 'deposit':
                $botman->startConversation($this->depositConversation);
                break;
            case 'withdraw':
                $botman->startConversation($this->withdrawConversation);
                break;
            case 'my currency':
                $this->helpCommand($botman);
                break;
            case 'change my currency':
                $botman->startConversation(new ChangeCurrencyConversation);
                break;
            default:
                $answer = "
                    Hey, I don't understand, maybe I can show to you all possible commands<br />
                    Write <b>help</b> to see all commands..
                ";
                $botman->reply($answer);
        }
    }


    public function askName($botman)
    {
        $botman->ask('Hello! What is your Name?', function (Answer $answer) {

            $name = $answer->getText();

            $this->say('Niceaaaaaa to meet you ' . $name);
        });
    }

    public function helpCommand($botman)
    {
        $message = "
            <b>help</b> to show all commands<br />
            <b>create new account</b> to register an account on ChatBank<br />
            <b>login</b> to enter on ChatBank, ex: <b>login</b> myuser password<br />
            <b>logout</b> to leave the ChatBank, ex: <b>login</b> myuser password<br />
            <b>my balance</b> to show your current balance<br />
            <b>deposit</b> to deposit a money on ChatBank, ex: <b>deposit</b> 10.00<br />
            <b>withdraw</b> to withdraw a money on ChatBank, ex: <b>withdraw</b> 15.36<br />
            <b>my currency</b> to show your current set currency<br />
            <b>change my currency</b> to select a new currency on ChatBank<br />

        ";
        $botman->reply($message);
    }

    public function myBalanceCommand($botman)
    {
        $botman->reply('Your balance is ' . number_format(Auth::user()->myBalance(), 2));
    }

    public function showCurrencyCommand($botman)
    {
        return;
    }
}
