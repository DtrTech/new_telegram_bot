<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/webhook/{telegram_bot_id}', [App\Http\Controllers\ApiController::class, 'webhook'])->name('webhook');