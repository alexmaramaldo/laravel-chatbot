<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home');

Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/refresh-token', function (Request $request) {
    session()->regenerate();
    return response()->json(
        [
            "token" => csrf_token()
        ],
        200
    );
});
