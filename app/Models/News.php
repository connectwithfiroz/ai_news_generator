<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'requested_at','response','summarize_response','original_image_url',
        'local_image_path','gemini_api_url',
        'published_at_whatsapp','published_url_whatsapp',
        'published_at_facebook','published_url_facebook',
        'published_at_linkedin','published_url_linkedin',
        'is_published','processed_at', 'batch_no', 'title', 'source', 'rewritten_title', 'rewritten_description', 'content', 'deleted_at'
    ];

    protected $casts = [
        'response' => 'array',
        'requested_at' => 'datetime',
        'published_at_whatsapp' => 'datetime',
        'published_at_facebook' => 'datetime',
        'published_at_linkedin' => 'datetime',
        'processed_at' => 'datetime',
        'is_published' => 'boolean',
        'summarize_response_json' => 'array',
    ];
}
