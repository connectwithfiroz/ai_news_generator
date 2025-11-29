<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenWallet extends Model
{
    protected $fillable = [
        'user_id',
        'total_token_available',
        'total_token_credited',
        'total_token_used',
    ];
}
