<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/send_message')->as('send_message.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'SendMessageController@index')->name('index');
    Route::get('/create', 'SendMessageController@create')->name('create');
    Route::post('/store', 'SendMessageController@store')->name('store');
    Route::get('/view/{sendMessage}', 'SendMessageController@view')->name('view');
    Route::get('/list/{sendMessage}', 'SendMessageController@list')->name('list');
    // Route::get('/edit/{send_message}', 'SendMessageController@edit')->name('edit');
    // Route::post('/update/{send_message}', 'SendMessageController@update')->name('update');
    // Route::get('/destroy/{send_message}', 'SendMessageController@destroy')->name('destroy');
});
