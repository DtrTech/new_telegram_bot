<?php

namespace App\Console\Commands;

use App\Models\SendMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckSendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:send-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and send message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendMessages = SendMessage::where('status', 'Pending')->get();

        if (!empty($sendMessages)) {
            foreach ($sendMessages as $sendMessage) {
                $sendMessage->update([
                    'status' => 'Sending'
                ]);
                foreach ($sendMessage->sendMessageDetails as $details) {
                    // send message
                    if ($sendMessage->message_id != null && $sendMessage->telegram_id != null) {

                    } elseif ($sendMessage->message_path != null) {

                    } else if ($sendMessage->message) {

                    }
                    $details->update([
                        'is_sent' => Carbon::now()
                    ]);
                }

                $sendMessage->update([
                    'status' => 'Sent'
                ]);
            }
        }

    }
}
