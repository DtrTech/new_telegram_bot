<?php

namespace App\Http\Controllers;

use App\Models\SendMessage;
use App\Models\TelegramBot;
use App\Models\TelegramGroup;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SendMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = SendMessage::all();
        return view('send_message.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $telegram_bots = TelegramBot::all();
        // $telegram_groups = TelegramGroup::all();
        // $telegram_users = TelegramUser::where('pm_bot', 1)->where('is_active', 1)->get();

        return view('send_message.create', compact('telegram_bots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sendMessage = SendMessage::create($request->all());
        if(isset($request->file_attachment)){
            $upload = $this->upload($request->file_attachment, 'send_message', $sendMessage->id);
            $sendMessage->update(['message_path' => $upload['file_path']]);
        }

        $telegram_bot = TelegramBot::find($request->telegram_bot_id);
        if (!$telegram_bot) {
            return back()->withInput()->withErrors('Telegram bot not found');
        }

        if ($sendMessage->message_type == 'All Groups') {
            $telegram_groups = $telegram_bot->groups->pluck('id');
            foreach ($telegram_groups as $group) {
                $sendMessage->sendMessageDetails()->create([
                    'content_type'  => "App\Models\TelegramGroup",
                    'content_id'    => $group,
                ]);
            }
        } elseif ($sendMessage->message_type == 'All Users') {
            $telegram_users = $telegram_bot->users->where('is_active', 1)->pluck('id');
            foreach ($telegram_users as $user) {
                $sendMessage->sendMessageDetails()->create([
                    'content_type'  => "App\Models\TelegramUser",
                    'content_id'    => $user,
                ]);
            }
        } elseif ($sendMessage->message_type == 'Groups') {
            foreach ($request->groups as $group) {
                $sendMessage->sendMessageDetails()->create([
                    'content_type'  => "App\Models\TelegramGroup",
                    'content_id'    => $group,
                ]);
            }
        } elseif ($sendMessage->message_type == 'Users') {
            foreach ($request->users as $user) {
                $sendMessage->sendMessageDetails()->create([
                    'content_type'  => "App\Models\TelegramUser",
                    'content_id'    => $user,
                ]);
            }
        }

        return redirect()->route('send_message.index')->withSuccess('Data saved');
    }

    public function view(Request $request, SendMessage $sendMessage)
    {
        $telegram_bots = TelegramBot::all();
        
        // Eager load only latest 100 details with their content
        $sendMessage->load([
            'sendMessageDetails' => function($query) {
                $query->latest()->limit(2000);
            },
            'sendMessageDetails.content'
        ]);
        
        return view('send_message.view', compact('sendMessage', 'telegram_bots'));
    }

    public function list(Request $request, SendMessage $sendMessage)
    {
        // Load only latest 100 details with their content
        $sendMessage->load([
            'sendMessageDetails' => function($query) {
                $query->latest()->limit(2000);
            },
            'sendMessageDetails.content'
        ]);
        
        return view('send_message.list', compact('sendMessage'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SendMessage $sendMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SendMessage $sendMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SendMessage $sendMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SendMessage $sendMessage)
    {
        //
    }
}
