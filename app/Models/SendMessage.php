<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'schedule_send_time',
        'message_type',
        'message_id',
        'telegram_id',
        'message_path',
        'message',
        'is_sent',
        'telegram_bot_id',
    ];

    public function sendMessageDetails()
    {
        return $this->hasMany(SendMessageDetail::class);
    }

    public function telegram_bot()
    {
        return $this->belongsTo(TelegramBot::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->schedule_send_time) {
                $model->schedule_send_time = now()->addMinute()->setSecond(0);
            }
        });
    }
}
