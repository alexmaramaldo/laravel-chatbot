<?php

namespace App\Conversations;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class RegisterConversation extends Conversation
{
    protected $name;

    protected $email;

    protected $password;

    protected $password_confirm;

    public function askName()
    {
        $this->ask('Hello! What is your name?', function (Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->say('Nice to meet you ' . $this->name);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('Now, I need your email, what is it?', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->askPassword();
        });
    }


    public function askPassword()
    {
        $this->ask('What is your password?', function (Answer $answer) {
            // Save result
            $this->password = $answer->getText();
            $this->askPasswordConfirm();
        });
    }

    public function askPasswordConfirm()
    {
        $this->ask('Can you confirm your password?', function (Answer $answer) {
            // Save result
            $this->password_confirm = $answer->getText();

            if ($this->password != $this->password_confirm) {
                $this->say('Hmmmm, password don\'t match, are you sure you typed correctly? Try again');
                $this->askPassword();
            } else {
                $this->say('Great - that is all we need, ' . $this->name);
                $this->say('You are logged now, ' . $this->name);
            }
        });
    }


    public function run()
    {
        // This will be called immediately
        $this->askName();
    }
}
