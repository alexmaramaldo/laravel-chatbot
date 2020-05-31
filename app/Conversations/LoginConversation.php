<?php

namespace App\Conversations;

use App\Services\AccountService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Auth;

class LoginConversation extends Conversation
{
    protected $email;
    protected $password;

    protected $accountService;

    /**
     * Create a new LoginConversation instance
     *
     * @param AccountService $accountService Dependency injection from repository layer
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Apply the the action to get the email
     *
     */
    public function askLogin()
    {
        $this->ask('Hey, send me your email', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->askPassword();
        });
    }

    /**
     * Apply the the action to get the password
     *
     */
    public function askPassword()
    {
        $this->ask('Now, I need your password,be careful, don\'t let anyone see', function (Answer $answer) {
            // Save result
            $this->password = $answer->getText();

            if ($data = $this->accountService->login($this->email, $this->password)) {
                $this->say('Welcome ' . $data['name'] . '|' . json_encode($data));
            } else {
                $this->say('Hmm, I don\'t found your email and password in our database.');
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
        $this->askLogin();
    }
}
