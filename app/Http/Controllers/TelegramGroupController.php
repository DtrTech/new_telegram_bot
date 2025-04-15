<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\TelegramGroup;
use App\Models\TelegramJoin;
use App\Models\TelegramUser;
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

    public function view(TelegramGroup $telegram_group)
    {
        $telegram_user_ids = TelegramJoin::where('telegram_group_id',$telegram_group->id)->orderBy('created_at',"DESC")->pluck('telegram_user_id')->toArray();
        $telegram_users = TelegramUser::whereIn('id',$telegram_user_ids)->get();
        // dd($telegram_user_ids);
        
        return view('telegram_group.view')->with('telegram_group',$telegram_group)->with('telegram_users',$telegram_users);
    }
}
