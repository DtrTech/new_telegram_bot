<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\SendMessageDetail;
use App\Models\TelegramGroup;
use App\Models\TelegramUser;
use App\Models\TelegramBotJoin;
use App\Models\SendMessage;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;
use Exception;
use Carbon\Carbon;

class TelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $controller;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i');
        $allMessage = SendMessage::whereRaw("DATE_FORMAT(schedule_send_time, '%Y-%m-%d %H:%i') = ?", [$now])->where('is_sent', 0)->get();

        foreach ($allMessage as $message){
            foreach($message->sendMessageDetails as $details){
                try {
                    if($details->content_type == "App\Models\TelegramGroup"){
                        $telegram_group = TelegramGroup::find($details->content_id);
                        $telegram_id = $telegram_group->group_telegram_id;
                        $telegram_bot_id = $message->telegram_bot_id;
                    }else{
                        $telegram_user = TelegramUser::find($details->content_id);
                        $telegram_id = $telegram_user->telegram_id;
                        $telegram_bot_id = $message->telegram_bot_id;
                    }

                    if($telegram_bot_id == 1){
                        $bot = Telegram::bot("mybot");
                    }else{
                        $bot = Telegram::bot("mybot{$telegram_bot_id}");
                    }

                    // Throttle API requests
                    usleep(350000); // 350ms delay (Telegram allows ~30 messages per second globally)

                    if($message->message_id != null && $message->telegram_id !=null){
                        $bot->forwardMessage([
                            'chat_id' => $telegram_id,
                            'from_chat_id' => $message->telegram_id,
                            'message_id' => $message->message_id,
                            'parse_mode' => 'HTML',
                            'disable_notification' => false,
                        ]);
                    }else{
                        $reply_markup = [];
                        $inline_keyboard = [];

                        if (!empty($message->button_link_1)) {
                            $inline_keyboard[] = [['text' => $message->button_text_1 ?? 'Button 1', 'url' => $message->button_link_1]];
                        }
                        if (!empty($message->button_link_2)) {
                            $inline_keyboard[] = [['text' => $message->button_text_2 ?? 'Button 2', 'url' => $message->button_link_2]];
                        }
                        if (!empty($message->button_link_3)) {
                            $inline_keyboard[] = [['text' => $message->button_text_3 ?? 'Button 3', 'url' => $message->button_link_3]];
                        }

                        // If inline keyboard buttons exist, add them to the reply_markup
                        if (!empty($inline_keyboard)) {
                            $reply_markup['inline_keyboard'] = $inline_keyboard;
                        }

                        if($message->message_path == null){
                            if (!empty($reply_markup)) {
                                $bot->sendMessage([
                                    'chat_id' => $telegram_id,
                                    'text' => $message->message,
                                    'parse_mode' => 'HTML',
                                    'reply_markup' => json_encode($reply_markup),
                                    'disable_notification' => false,
                                ]);
                            }else{
                                $bot->sendMessage([
                                    'chat_id' => $telegram_id,
                                    'text' => $message->message,
                                    'parse_mode' => 'HTML',
                                    'disable_notification' => false,
                                ]);
                            }
                        }else{
                            $extension = pathinfo($message->message_path, PATHINFO_EXTENSION);

                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                            $videoExtensions = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'flv'];
                            $type = "unknown";
                            if (in_array($extension, $imageExtensions)) {
                                $type = 'image';
                            } elseif (in_array($extension, $videoExtensions)) {
                                $type = 'video';
                            }

                            if($type == "video"){
                                $video = asset('storage/' . $message->message_path);
                                $video_to_set = InputFile::create($video,'video.'.$extension);
                                
                                if (!empty($reply_markup)) {
                                    $bot->sendVideo([
                                        'chat_id' => $telegram_id,
                                        'video' => $video_to_set,
                                        'caption' => $message->message,
                                        'parse_mode' => 'HTML',
                                        'reply_markup' => json_encode($reply_markup),
                                        'disable_notification' => false,
                                    ]);
                                }else{
                                    $bot->sendVideo([
                                        'chat_id' => $telegram_id,
                                        'video' => $video_to_set,
                                        'caption' => $message->message,
                                        'parse_mode' => 'HTML',
                                        'disable_notification' => false,
                                    ]);
                                }
                            }else if($type == "image"){
                                $images = asset('storage/' . $message->message_path);
                                $image_to_set = InputFile::create($images,'image.'.$extension);
                                
                                if (!empty($reply_markup)) {
                                    $bot->sendPhoto([
                                        'chat_id' => $telegram_id,
                                        'photo' => $image_to_set,
                                        'caption' => $message->message,
                                        'parse_mode' => 'HTML',
                                        'reply_markup' => json_encode($reply_markup),
                                        'disable_notification' => false,
                                    ]);
                                }else{
                                    $bot->sendPhoto([
                                        'chat_id' => $telegram_id,
                                        'photo' => $image_to_set,
                                        'caption' => $message->message,
                                        'parse_mode' => 'HTML',
                                        'disable_notification' => false,
                                    ]);
                                }
                            }
                        }
                    }
                    $details->update(['sent_at' => Carbon::now()]);

                } catch (\Exception $e) {
                    Log::error("Failed to send message detail ID {$details->id}: " . $e->getMessage());

                    if ($e->getCode() == 429) { // Handle "Too Many Requests"
                        $retryAfter = json_decode($e->getMessage(), true)['parameters']['retry_after'] ?? 5;
                        sleep($retryAfter); // Wait for the specified time before retrying
                    }

                    continue;
                }
            }
            $message->update(['is_sent' => 1]);
        }
    }
}
