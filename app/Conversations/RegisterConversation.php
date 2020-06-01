<?php

namespace App\Conversations;

use App\Services\AccountService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Exception;

class RegisterConversation extends Conversation
{
    protected $name;
    protected $email;
    protected $password;
    protected $password_confirm;

    protected $accountService;

    /**
     * Create a new RegisterConversation instance
     *
     * @param AccountService $accountService Dependency injection from repository layer
     *
     * @return void
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Apply the the action to get the name
     *
     */
    public function askName()
    {
        $this->ask('Hello! What is your name?', function (Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->say('Nice to meet you ' . $this->name);
            $this->askEmail();
        });
    }

    /**
     * Apply the the action to get the email
     *
     */
    public function askEmail()
    {
        $this->ask('Now, I need your email, what is it?', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->say("Email address '" . $this->email . "' is invalid, try again....\n");
                return $this->askEmail();
            }

            return $this->askPassword();
        });
    }

    /**
     * Apply the the action to get the password
     *
     */
    public function askPassword()
    {
        $this->ask('Inform your password...', function (Answer $answer) {
            // Save result
            $this->password = $answer->getText();
            $this->askPasswordConfirm();
        });
    }

    /**
     * Apply the the action to get the password_confirm
     *
     */
    public function askPasswordConfirm()
    {
        $this->ask('Confirm your password', function (Answer $answer) {
            // Save result
            $this->password_confirm = $answer->getText();

            if ($this->password != $this->password_confirm) {
                $this->say('Hmmmm, password don\'t match, are you sure you typed correctly? Try again');
                $this->askPassword();
            } else {
                $parameters = [
                    "name" => $this->name,
                    "email" => $this->email,
                    "password" => $this->password
                ];

                try {
                    $data = $this->accountService->register($parameters);

                    $this->say('Great - that is all we need, ' . $this->name . '|' . json_encode($data));
                    $this->say('You are logged now');
                } catch (Exception $e) {
                    if ($e->getCode() == 400) {
                        $this->say('Houston, we have a problem, check bellow...<br /><b> - ' . $e->getMessage() . '</b>');
                    } else {
                        $this->say('Houston, we have a problem, this is so crazy. Try again please....');
                        $this->askName();
                    }
                }
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
        $this->askName();
    }
}
