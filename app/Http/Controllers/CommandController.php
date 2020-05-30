<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Services\BotCommandService;

class CommandController extends Controller
{

    private $botCommandService;

    public function __construct(BotCommandService $botCommandService)
    {
        $this->botCommandService = $botCommandService;
    }


    public function actionCommands(BotMan $botman, $message)
    {
        $this->botCommandService->listenCommand($botman, $message);

        // $botman->reply("Invalid parameters");
    }
}
