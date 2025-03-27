<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/telegram_group')->as('telegram_group.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'TelegramGroupController@index')->name('index');
});
