<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'response_data',
    ];
    
    protected $casts = [
        'response_data' => 'array',
    ];
}
