<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiKnowledge extends Model
{
    protected $table = 'ai_knowledge';

    protected $fillable = [
        'category', 'source_type', 'source_id', 'title', 'content',
        'source_url', 'keywords', 'metadata', 'checksum', 'indexed_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'metadata' => 'array',
        'indexed_at' => 'datetime',
    ];
}