<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSearchLog extends Model
{
    protected $table = 'ai_search_logs';

    protected $fillable = [
        'query', 'normalized_query', 'intent', 'entities',
        'keywords', 'matched', 'confidence', 'answered', 'duration_ms',
    ];

    protected $casts = [
        'entities'     => 'array',
        'keywords'     => 'array',
        'matched'      => 'array',
        'confidence'   => 'float',
        'answered'     => 'boolean',
        'duration_ms'  => 'integer',
    ];
}