<?php

namespace App\Conversations;

use App\Services\AccountService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Auth;

class LogoutConversation extends Conversation
{

    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function logout()
    {

        $this->accountService->logout();

        $this->say('I will miss you, come back here whenever possible, I like to talk to you!');
        $this->say('Bye...............');
    }


    public function run()
    {
        // This will be called immediately
        $this->logout();
    }
}
