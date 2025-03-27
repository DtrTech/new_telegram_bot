<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/telegram_user')->as('telegram_user.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'TelegramUserController@index')->name('index');
    Route::get('/load', 'TelegramUserController@load')->name('load');
    Route::get('/view_message/{telegram_user}', 'TelegramUserController@view_message')->name('view_message');
    Route::get('/load_message/{telegram_user}', 'TelegramUserController@load_message')->name('load_message');
    Route::post('/toggleStatus', 'TelegramUserController@toggleStatus')->name('toggleStatus');
});
