<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;


class BotManController extends Controller
{
    /**
     * Creating the Listern to the BotMan
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }
}
