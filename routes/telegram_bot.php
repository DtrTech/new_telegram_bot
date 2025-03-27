<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/telegram_bot')->as('telegram_bot.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'TelegramBotController@index')->name('index');
    Route::get('/create', 'TelegramBotController@create')->name('create');
    Route::post('/store', 'TelegramBotController@store')->name('store');
    Route::get('/edit/{telegram_bot}', 'TelegramBotController@edit')->name('edit');
    Route::post('/update/{telegram_bot}', 'TelegramBotController@update')->name('update');
    Route::get('/destroy/{telegram_bot}', 'TelegramBotController@destroy')->name('destroy');
    Route::get('/connect/{telegram_bot}', 'TelegramBotController@connect')->name('connect');

    Route::get('/getGroupList/{telegram_bot}', 'TelegramBotController@getGroupList')->name('getGroupList');
    Route::get('/getUserList/{telegram_bot}', 'TelegramBotController@getUserList')->name('getUserList');
});
