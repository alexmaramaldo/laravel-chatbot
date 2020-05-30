<?php

namespace App\Conversations;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class LoginConversation extends Conversation
{
    protected $email;

    protected $password;

    protected $code;


    public function askLogin()
    {
        $this->ask('Hey, send me your email', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            //Check if email exist


            $this->askPassword();
        });
    }

    public function askPassword()
    {
        $this->ask('Now, I need your password,be careful, don\'t let anyone see', function (Answer $answer) {
            // Save result
            $this->password = $answer->getText();

            $this->say('Perfect NAME, for security reasons, we send a code to your email.');

            $this->askCode();
        });
    }


    public function askCode()
    {
        $this->ask('What is the security CODE? You have 30 seconds to inform it!', function (Answer $answer) {
            // Save result
            $this->code = $answer->getText();

            $this->say('You\'re logged successfully.');
        });
    }


    public function run()
    {
        // This will be called immediately
        $this->askLogin();
    }
}
