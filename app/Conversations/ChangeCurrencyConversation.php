<?php

namespace App\Conversations;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class ChangeCurrencyConversation extends Conversation
{
    protected $current_currency;

    protected $code;


    public function askCurrency()
    {
        $message = "
            You selected to change you currency, see bellow all currencies allowed on the bank<br />
            <b>1</b> - Real BRL<br />
            <b>2</b> - Dolar USA<br />
            <b>3</b> - Dolar CAN<br />
            <b>4</b> - Euro EUR<br />

        ";
        $this->say($message);
        $this->ask('Choose a option', function (Answer $answer) {
            // Save result
            $this->code = $answer->getText();

            //Logic to change it

            $this->say('Currency updated');
        });
    }


    public function run()
    {
        // This will be called immediately
        $this->askCurrency();
    }
}
