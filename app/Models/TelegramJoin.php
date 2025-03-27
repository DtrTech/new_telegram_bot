<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TelegramJoin extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'telegram_group_id',
        'telegram_user_id',
    ];
    
    public function group_dt()
    {
        return $this->belongsTo('App\Models\TelegramGroup','telegram_group_id');
    }
    
    public function telegram_register()
    {
        return $this->belongsTo('App\Models\TelegramRegister');
    }
}
