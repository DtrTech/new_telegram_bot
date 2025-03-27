<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMessageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'send_message_id',
        'content_type',
        'content_id',
        'sent_at'
    ];

    public function sendMessage()
    {
        return $this->belongsTo(SendMessage::class);
    }

    public function content()
    {
        return $this->morphTo();
    }
}
