<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TelegramGroup;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'telegram_user_id',
        'message_id',
        'send_to_chat_id',
        'send_from_chat_id',
        'datetime',
        'text',
        'telegram_bot_id'
    ];

    protected $dates = ['deleted_at'];

    public function telegram_user()
    {
        return $this->belongsTo('App\Models\TelegramUser');
    }

    public function telegram_bot()
    {
        return $this->belongsTo('App\Models\TelegramBot');
    }

    public function getMessageTimeAttribute()
    {
        return Carbon::createFromTimestamp($this->datetime)->format('Y-m-d H:i:s');
    }

    public function getChatTypeAttribute()
    {
        if($this->send_to_chat_id == $this->send_from_chat_id){
            return $this->telegram_bot->bot_name ?? 'Bot';
        }else{
            $group = TelegramGroup::where('group_telegram_id',$this->send_from_chat_id)->first();

            return $group->group_name ?? '';
        }
    }
}
