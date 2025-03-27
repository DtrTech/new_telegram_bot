<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramBot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bot_name',
        'bot_token',
        'connected',
        'reply_message_path',
        'reply_message',
        'reply_message_by_message_id',
        'reply_message_from_telegram_id',
        'button_text_1',
        'button_link_1',
        'button_text_2',
        'button_link_2',
        'button_text_3',
        'button_link_3',
    ];

    public function groups()
    {
        return $this->hasManyThrough(
            'App\Models\TelegramGroup', // The related model
            'App\Models\TelegramBotJoin', // The intermediate model
            'telegram_bot_id', // Foreign key on TelegramBotJoin
            'id', // Foreign key on TelegramGroup
            'id', // Local key on TelegramBot
            'telegram_group_id' // Local key on TelegramBotJoin
        );
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\Models\TelegramUser', // The related model
            'App\Models\TelegramUserBot', // The intermediate model
            'telegram_bot_id', // Foreign key on TelegramUserBot
            'id', // Foreign key on TelegramUser
            'id', // Local key on TelegramBot
            'telegram_user_id' // Local key on TelegramUserBot
        );
    }
}
