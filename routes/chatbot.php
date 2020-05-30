<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$botman = resolve('botman');

$botman->hears('Hi|Hello|Hey', function ($bot) {
    $bot->reply("Hello " . Auth::user()['name'] . "! My name is Botman and I will help you with the transactions.");
});

$botman->hears('{message}', 'App\Http\Controllers\CommandController@actionCommands');

// $botman->hears('Available currencies', 'App\Http\Controllers\CurrencyController@availableCurrencies');
// $botman->hears('Set default currency {currency}', 'App\Http\Controllers\CurrencyController@setDefault');
// $botman->hears('Deposit {value}', 'App\Http\Controllers\AccountController@deposit');
// $botman->hears('Withdraw {value}', 'App\Http\Controllers\AccountController@withdraw');
// $botman->hears('Show account balance', 'App\Http\Controllers\AccountController@accountBalance');

$botman->fallback(function ($bot) {
    $bot->reply("Unknown command.");
});


// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });
