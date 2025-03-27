<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/settings')->as('setting.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'SettingController@index')->name('index');
    Route::get('/create', 'SettingController@create')->name('create');
    Route::post('/store', 'SettingController@store')->name('store');
    Route::get('/edit/{setting}', 'SettingController@edit')->name('edit');
    Route::post('/update/{setting}', 'SettingController@update')->name('update');
});
