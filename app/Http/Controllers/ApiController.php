<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserAngpao;
use App\Models\TelegramBot;
use App\Models\TelegramUser;
use App\Models\TelegramRequest;
use App\Models\Message;
use App\Models\Setting;
use App\Models\TelegramGroup;
use App\Models\TelegramBotJoin;
use App\Models\TelegramJoin;
use App\Models\TelegramUserBot;
use App\Models\UserBonus;
use App\Models\UserFirstDeposit;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

class ApiController extends Controller
{
    public function webhook(Request $request, $telegram_bot_id)
    {
        $bot_updates = Telegram::getWebhookUpdate();

        $telegram = TelegramRequest::create([
            'response_data'=>$bot_updates
        ]);
        if($telegram_bot_id == 1){
            $bot = Telegram::bot("mybot");
        }else{
            $bot = Telegram::bot("mybot{$telegram_bot_id}");
        }

        $telegram_bot = TelegramBot::find($telegram_bot_id);

        $data = $telegram->response_data;

        if(isset($data['my_chat_member'])){
            $chat_id = $data['my_chat_member']['from']['id'] ?? null;
            $first_name = $data['message']['from']['first_name']??'';
            $last_name = $data['message']['from']['last_name']??'';
            $username = $data['message']['from']['username']??'';
            $telegram_user = TelegramUser::where('telegram_id',$chat_id)->first();
            if(!isset($telegram_user)){
                $telegram_user = TelegramUser::create([
                    'telegram_bot_id'=>$telegram_bot->id,
                    'telegram_id'=>$chat_id,
                    'first_name'=>$first_name,
                    'last_name'=>$last_name,
                    'username'=>$username,
                ]);
            }
            $group_id = $data['my_chat_member']['chat']['id'] ?? null;
            $title = $data['my_chat_member']['chat']['title'] ?? null;
            $type = $data['my_chat_member']['chat']['type'] ?? null;
            if ($group_id && $title && $type) {
                $telegram_group = TelegramGroup::firstOrCreate(
                    ['group_telegram_id' => $group_id],
                    [
                        'group_name' => $title,
                        'group_type' => $type,
                    ]
                );
                if(isset($data['my_chat_member']['new_chat_member']['user']['username'])){
                    if($data['my_chat_member']['new_chat_member']['user']['username'] == $telegram_bot->bot_name){
                        TelegramBotJoin::updateOrCreate(
                            [
                                'telegram_bot_id' => $telegram_bot->id,
                                'telegram_group_id' => $telegram_group->id,
                            ]
                        );
                    }
                }
            }
            return response()->json(['status' => 'ok']);
        }

        if(isset($data['message'])){
            $chat_id = $data['message']['from']['id']??'';
            $from_chat_id = $data['message']['chat']['id']??'';
            $first_name = $data['message']['from']['first_name']??'';
            $last_name = $data['message']['from']['last_name']??'';
            $username = $data['message']['from']['username']??'';
            $language_code = $data['message']['from']['language_code']??'';
            $text = $data['message']['text']??'';
            $caption = $data['message']['caption']??'';
            $date = $data['message']['date']??'';
            $messageId = $data['message']['message_id']??'';
            $is_bot = $data['message']['from']['is_bot'] == true?1:0;

            if($chat_id == $from_chat_id){
                $telegram_user = TelegramUser::where('telegram_id',$chat_id)->first();
                if(!isset($telegram_user)){
                    $telegram_user = TelegramUser::create([
                        'telegram_bot_id'=>$telegram_bot->id,
                        'telegram_id'=>$chat_id,
                        'first_name'=>$first_name,
                        'last_name'=>$last_name,
                        'username'=>$username,
                        'pm_bot'=>1,
                    ]);

                    TelegramUserBot::firstOrCreate([
                        'telegram_user_id' => $telegram_user->id,
                        'telegram_bot_id' => $telegram_bot->id,
                    ]);
                }else{
                    if($telegram_user->pm_bot == 0){
                        $telegram_user->update(['pm_bot'=>1]);
                    }

                    TelegramUserBot::firstOrCreate([
                        'telegram_user_id' => $telegram_user->id,
                        'telegram_bot_id' => $telegram_bot->id,
                    ]);
                }

                if(isset($data['message']['caption'])){
                    $message = Message::create([
                        'telegram_user_id'=>$telegram_user->id,
                        'message_id'=>$messageId,
                        'send_to_chat_id'=>$chat_id,
                        'send_from_chat_id'=>$from_chat_id,
                        'datetime'=>$date,
                        'text'=>$caption,
                        'telegram_bot_id' => $telegram_bot_id,
                    ]);
                }else if(isset($data['message']['text'])){
                    $message = Message::create([
                        'telegram_user_id'=>$telegram_user->id,
                        'message_id'=>$messageId,
                        'send_to_chat_id'=>$chat_id,
                        'send_from_chat_id'=>$from_chat_id,
                        'datetime'=>$date,
                        'text'=>$text,
                        'telegram_bot_id' => $telegram_bot_id,
                    ]);
                }

                // if(isset($data['message']['contact'])){
                //     $phone_number = $data['message']['contact']['phone_number'];

                //     $telegram_user->update([
                //         'contact_no'=>$phone_number,
                //     ]);
                // }

                // if($telegram_user->contact_no == null){
                //     $reply_markup = [
                //         'keyboard' => [
                //             [
                //                 [
                //                     'text' => "Share number for Better Verification",
                //                     'request_contact' => true,
                //                 ]
                //             ]
                //         ],
                //         'resize_keyboard' => true,
                //         'one_time_keyboard' => true,
                //     ];
                // }else{
                //     $reply_markup = ['remove_keyboard' => true];
                // }
                $reply_markup = [];
                if($telegram_bot->reply_message_by_message_id != null && $telegram_bot->reply_message_from_telegram_id != null){
                    $bot->forwardMessage([
                        'chat_id' => $chat_id,
                        'from_chat_id' => $telegram_bot->reply_message_from_telegram_id,
                        'message_id' => $telegram_bot->reply_message_by_message_id  ,
                        // 'reply_markup' => json_encode($reply_markup),
                        'parse_mode' => 'HTML'
                    ]);
                }else if($telegram_bot->reply_message_path != null){
                    $extension = pathinfo($telegram_bot->reply_message_path, PATHINFO_EXTENSION);
                    $images = asset('storage/' . $telegram_bot->reply_message_path);
                    $image_to_set = InputFile::create($images,'image.'.$extension);
                    
                    $inline_keyboard = [];

                    if (!empty($telegram_bot->button_link_1)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_1 ?? 'Button 1', 'url' => $telegram_bot->button_link_1]];
                    }
                    if (!empty($telegram_bot->button_link_2)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_2 ?? 'Button 2', 'url' => $telegram_bot->button_link_2]];
                    }
                    if (!empty($telegram_bot->button_link_3)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_3 ?? 'Button 3', 'url' => $telegram_bot->button_link_3]];
                    }

                    // If inline keyboard buttons exist, add them to the reply_markup
                    if (!empty($inline_keyboard)) {
                        $reply_markup['inline_keyboard'] = $inline_keyboard;
                    }

                    if (!empty($reply_markup)) {
                        $bot->sendPhoto([
                            'chat_id' => $chat_id,
                            'photo' => $image_to_set,
                            'caption' => $telegram_bot->reply_message,
                            'reply_markup' => json_encode($reply_markup),
                            'parse_mode' => 'HTML'
                        ]);
                    }else{
                        $bot->sendPhoto([
                            'chat_id' => $chat_id,
                            'photo' => $image_to_set,
                            'caption' => $telegram_bot->reply_message,
                            'parse_mode' => 'HTML'
                        ]);
                    }
                }else if ($telegram_bot->reply_message != null) {
                    $inline_keyboard = [];

                    if (!empty($telegram_bot->button_link_1)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_1 ?? 'Button 1', 'url' => $telegram_bot->button_link_1]];
                    }
                    if (!empty($telegram_bot->button_link_2)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_2 ?? 'Button 2', 'url' => $telegram_bot->button_link_2]];
                    }
                    if (!empty($telegram_bot->button_link_3)) {
                        $inline_keyboard[] = [['text' => $telegram_bot->button_text_3 ?? 'Button 3', 'url' => $telegram_bot->button_link_3]];
                    }

                    // If inline keyboard buttons exist, add them to the reply_markup
                    if (!empty($inline_keyboard)) {
                        $reply_markup['inline_keyboard'] = $inline_keyboard;
                    }
                    
                    if (!empty($reply_markup)) {
                        $bot->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $telegram_bot->reply_message,
                            'reply_markup' => json_encode($reply_markup),
                            'parse_mode' => 'HTML'
                        ]);
                    }else{
                        $bot->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $telegram_bot->reply_message,
                            'parse_mode' => 'HTML'
                        ]);
                    }
                }
                return response()->json(['status' => 'ok']);
            }else{

                if(isset($data['message']['text']) || isset($data['message']['caption'])){
                    $telegram_user = TelegramUser::where('telegram_id',$chat_id)->first();
                    if(!isset($telegram_user)){
                        $telegram_user = TelegramUser::create([
                            'telegram_bot_id'=>$telegram_bot->id,
                            'telegram_id'=>$chat_id,
                            'first_name'=>$first_name,
                            'last_name'=>$last_name,
                            'username'=>$username,
                        ]);
                    }

                    if(isset($data['message']['caption'])){
                        $message = Message::create([
                            'telegram_user_id'=>$telegram_user->id,
                            'message_id'=>$messageId,
                            'send_to_chat_id'=>$chat_id,
                            'send_from_chat_id'=>$from_chat_id,
                            'datetime'=>$date,
                            'text'=>$caption,
                            'telegram_bot_id' => $telegram_bot_id,
                        ]);
                    }else if(isset($data['message']['text'])){
                        $message = Message::create([
                            'telegram_user_id'=>$telegram_user->id,
                            'message_id'=>$messageId,
                            'send_to_chat_id'=>$chat_id,
                            'send_from_chat_id'=>$from_chat_id,
                            'datetime'=>$date,
                            'text'=>$text,
                            'telegram_bot_id' => $telegram_bot_id,
                        ]);
                    }

                    if (isset($message)) {
                        $settings = Setting::all();

                        // check telegram_id
                        $telegramIdSetting = $settings->where('key', 'telegram_id')->first();
                        if ($telegramIdSetting && !empty($telegramIdSetting->value)) {
                            $telegramIdsArray = explode(',', $telegramIdSetting->value);
                            $telegramIdsArray = array_filter(array_map('trim', $telegramIdsArray));
                        }

                        // do if telegram user id not in list
                        if (empty($telegramIdsArray) || !in_array($telegram_user->telegram_id, $telegramIdsArray)) {
                            // check remove_link
                            $removeLinkSetting = $settings->where('key', 'remove_link')->first();
                            if ($removeLinkSetting->value == 'Yes') {
                                $containsLink = preg_match('/(?:https?:\/\/|www\.|[a-zA-Z0-9-]+\.[a-zA-Z]{2,})(?:[^\s]*)/', $message->text);
                                if ($containsLink) {
                                    try {
                                        $bot->deleteMessage([
                                            'chat_id' => $from_chat_id,
                                            'message_id' => $messageId,
                                        ]);
                                        $message->delete();

                                        // // Optional: Notify the group
                                        // $reason = $containsLink ? 'Links are not allowed' : 'Inappropriate language is not allowed';
                                        // $bot->sendMessage([
                                        //     'chat_id' => $from_chat_id,
                                        //     'text' => "🚫 Message deleted: $reason.",
                                        // ]);
                                    } catch (\Exception $e) {
                                        Log::error('Failed to delete message: ' . $e->getMessage());
                                    }
                                }
                            }

                            // check filter_word
                            $filterWordSetting = $settings->where('key', 'filter_word')->first();
                            $containsFilterWord = false;
                            if ($filterWordSetting && !empty($filterWordSetting->value)) {
                                $filterWords = explode('|', $filterWordSetting->value);
                                foreach ($filterWords as $word) {
                                    if (preg_match("/\b" . preg_quote($word, '/') . "\b/i", $message->text)) {
                                        $containsFilterWord = true;
                                        break;
                                    }
                                }
                            }

                            if ($containsFilterWord) {
                                try {
                                    $bot->deleteMessage([
                                        'chat_id' => $from_chat_id,
                                        'message_id' => $messageId,
                                    ]);
                                    $message->delete();

                                    // // Optional: Notify the group
                                    // $reason = $containsLink ? 'Links are not allowed' : 'Inappropriate language is not allowed';
                                    // $bot->sendMessage([
                                    //     'chat_id' => $from_chat_id,
                                    //     'text' => "🚫 Message deleted: $reason.",
                                    // ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to delete message: ' . $e->getMessage());
                                }
                            }
                        }
                    }

                    $telegram_group = TelegramGroup::where('group_telegram_id',$from_chat_id)->first();
                    if(isset($telegram_group)){
                        TelegramJoin::firstOrCreate([
                            'telegram_user_id' => $telegram_user->id,
                            'telegram_group_id' => $telegram_group->id
                        ]);
                    }
                }

                if(isset($data['message']['new_chat_members'])){
                    foreach($data['message']['new_chat_members'] as $a){
                        $telegram_group = TelegramGroup::where('group_telegram_id',$from_chat_id)->first();
                        $chat_id = $a['id'];
                        $is_bot = $a['is_bot'] == true?1:0;
                        $telegram_user = TelegramUser::where('telegram_id',$chat_id)->first();
                        if($is_bot != 1){
                            if(!isset($telegram_user)){
                                $telegram_user = TelegramUser::create([
                                    'telegram_bot_id'=>$telegram_bot->id,
                                    'telegram_id'=>$chat_id,
                                    'first_name'=>$a['first_name']??'',
                                    'last_name'=>$a['last_name']??'',
                                    'username'=>$a['username']??'',
                                ]);
                            }
                            if(isset($telegram_group)){
                                TelegramJoin::firstOrCreate([
                                    'telegram_user_id' => $telegram_user->id,
                                    'telegram_group_id' => $telegram_group->id
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return 'True';
    }
}
