<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\TelegramGroup;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use GuzzleHttp\Client;

class TelegramGroupController extends Controller
{
    public function index(Request $request)
    {
        $telegram_group = TelegramGroup::all();
        
        return view('telegram_group.index')->with('telegram_group',$telegram_group);
    }
}
