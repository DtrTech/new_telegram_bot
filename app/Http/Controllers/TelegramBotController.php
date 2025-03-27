<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\TelegramBot;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use GuzzleHttp\Client;
use DB;

class TelegramBotController extends Controller
{
    public function index(Request $request)
    {
        $telegram_bot = TelegramBot::all();

        return view('telegram_bot.index')->with('telegram_bot',$telegram_bot);
    }

    public function create()
    {
        $check = TelegramBot::all()->count();
        if($check >=10){
            return redirect()->route('telegram_bot.index')->withErrors('Max 10 Bot');
        }
        return view('telegram_bot.create');
    }

    public function store(Request $request)
    {
        $request->merge(['created_by_id'=>Auth::user()->id]);
        $telegram_bot = TelegramBot::create($request->all());
        if(isset($request->file_attachment)){
            $upload = $this->upload($request->file_attachment, 'telegram_bot', $telegram_bot->id);
            $telegram_bot->update(['reply_message_path'=>$upload['file_path']]);
        }
        return redirect()->route('telegram_bot.index')->withSuccess('Data saved');
    }

    public function edit(TelegramBot $telegram_bot)
    {
        return view('telegram_bot.create')->with('telegram_bot',$telegram_bot);
    }

    public function update(Request $request, TelegramBot $telegram_bot)
    {
        if($request->bot_token != $telegram_bot->bot_token){
            $request->merge(['connected'=>0]);
        }
        $telegram_bot->update($request->all());
        if(isset($request->file_attachment)){
            $upload = $this->upload($request->file_attachment, 'telegram_bot', $telegram_bot->id);
            $telegram_bot->update(['reply_message_path'=>$upload['file_path']]);
        }
        return redirect()->route('telegram_bot.index')->withSuccess('Data updated');
    }

    public function destroy(TelegramBot $telegram_bot)
    {
        $telegram_bot->delete();
        return redirect()->route('telegram_bot.index')->withSuccess('Data deleted');

    }

    public function connect(TelegramBot $telegram_bot)
    {
        $baseUrl = url('/');
        // dd($baseUrl);
        $url = $baseUrl."/api/webhook/".$telegram_bot->id;

        $client = new Client();

        $response = $client->post('https://api.telegram.org/bot'.$telegram_bot->bot_token.'/setWebhook', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'url' => $url,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $telegram_bot->update(['connected'=>1]);
            $envPath = base_path('.env');
            $newTokenLine = 'TELEGRAM_' . $telegram_bot->id . '="' . $telegram_bot->bot_token . '"' . PHP_EOL;

            if (File::exists($envPath)) {
                $envContents = File::get($envPath);

                $pattern = '/^TELEGRAM_' . preg_quote($telegram_bot->id, '/') . '=.*$/m';
                if (preg_match($pattern, $envContents)) {
                    $envContents = preg_replace($pattern, trim($newTokenLine), $envContents);
                } else {
                    $envContents .= $newTokenLine;
                }

                File::put($envPath, $envContents);
            }
        } else {
            return "Failed";
        }
        return redirect()->route('telegram_bot.index')->withSuccess('Data deleted');

    }

    public function getGroupList(TelegramBot $telegram_bot)
    {
        $groupList = $telegram_bot->groups->pluck('group_name', 'id');
        return $groupList;
    }

    public function getUserList(TelegramBot $telegram_bot)
    {
        $userList = $telegram_bot->users->where('is_active', 1)->mapWithKeys(function ($user) {
            $fullName = $user->first_name . ' ' . $user->last_name;
            return [$user->id => $fullName];
        });
        return $userList;
    }
}
