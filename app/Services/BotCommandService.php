<?php

namespace App\Services;

use App\Conversations\DepositConversation;
use App\Conversations\LoginConversation;
use App\Conversations\LogoutConversation;
use App\Conversations\RegisterConversation;
use App\Conversations\WithdrawConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * BotCommandService is the main class with a routes to distributed the mains actions on the chat
 */
class BotCommandService
{
    private $registerConversation;
    private $loginConversation;
    private $logoutConversation;
    private $depositConversation;
    private $withdrawConversation;

    /**
     * Create a new BotCommandService instance
     *
     * @param RegisterConversation $registerConversation Dependency injection from model layer
     * @param LoginConversation $loginConversation Dependency injection from model layer
     * @param LogoutConversation $logoutConversation Dependency injection from model layer
     * @param DepositConversation $depositConversation Dependency injection from model layer
     * @param WithdrawConversation $withdrawConversation Dependency injection from model layer
     *
     * @return void
     */
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

    /**
     * listenCommand - Method to save the user on database
     *
     * @param BotMan $botman Instance with botman methods
     * @param string $message Informed by User
     *
     * @return mixed
     */
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
            case 'list currencies':
                $this->isLogged($botman) ? $this->listCurrencies($botman) : '';
                break;
            default:
                $answer = "
                    Hey, I don't understand, maybe I can show to you all possible commands<br />
                    Write <b>help</b> to see all commands..
                ";
                $botman->reply($answer);
        }
    }

    /**
     * helpCommand - Show all commands allowed on chat
     *
     * @param BotMan $botman BotMan instance
     *
     * @return mixed
     */
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
            <b>list currencies</b> to list all allowed currencies on ChatBank<br />

        ";
        $botman->reply($message);
    }

    /**
     * myBalanceCommand - Show the current Balance from the user
     *
     * @param BotMan $botman BotMan instance
     *
     * @return mixed
     */
    public function myBalanceCommand($botman)
    {
        $botman->reply('Your balance is ' . Auth::user()->currency . ' ' . number_format(Auth::user()->myBalance(), 2));
    }

    /**
     * listCurrencies - List all currencies allowed
     *
     * @param BotMan $botman BotMan instance
     *
     * @return mixed
     */
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

    /**
     * isLogged - Method to check if user is logged and send a message
     *
     * @param BotMan $botman BotMan instance
     *
     * @return bool
     */
    public function isLogged($botman): bool
    {
        if (!Auth::check()) {
            $botman->reply("Hey, I can't to do it, you need are logged on ChatBank to execute this command! Type <b>login</b> or <b>help</b> to see all commands!!!!");
            return false;
        } else {
            return true;
        }
    }
}
