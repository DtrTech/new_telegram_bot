<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'group_telegram_id',
        'group_name',
        'group_type',
    ];
    
    public function bots()
    {
        return $this->hasMany('App\Models\TelegramBotJoin');
    }
    
    public function getBotNamesAttribute()
    {
        return $this->bots->flatMap(function ($bots) {
            return $bots->telegram_bot ? [$bots->telegram_bot->bot_name] : [];
        })->implode(', ');
    }
}
