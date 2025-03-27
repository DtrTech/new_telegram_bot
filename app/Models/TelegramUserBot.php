<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUserBot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'telegram_user_id',
        'telegram_bot_id'
    ];

    public function group_dt()
    {
        return $this->belongsTo('App\Models\TelegramUser','telegram_user_id');
    }

    public function telegram_bot()
    {
        return $this->belongsTo('App\Models\TelegramBot');
    }
}
