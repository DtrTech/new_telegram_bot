<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'telegram_bot_id',
        'telegram_id',
        'first_name',
        'last_name',
        'username',
        'contact_no',
        'pm_bot',
        'is_active'
    ];

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function groups()
    {
        return $this->hasMany('App\Models\TelegramJoin');
    }

    public function getGroupNamesAttribute()
    {
        return $this->groups->flatMap(function ($group) {
            return $group->group_dt ? [$group->group_dt->group_name] : [];
        })->implode(', ');
    }

    public function bots()
    {
        return $this->hasMany('App\Models\TelegramUserBot');
    }

    public function getBotNamesAttribute()
    {
        return $this->bots->flatMap(function ($bot) {
            return $bot->telegram_bot ? [$bot->telegram_bot->bot_name] : [];
        })->implode(', ');
    }

    // no use
    public function bot()
    {
        return $this->belongsTo('App\Models\TelegramBot', 'telegram_bot_id');
    }
}
