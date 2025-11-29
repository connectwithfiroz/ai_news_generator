<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenUsageLog extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'token_used',
        'request_meta',
    ];
}
