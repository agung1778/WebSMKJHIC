<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSearchLog extends Model
{
    protected $table = 'ai_search_logs';

    protected $fillable = [
        'question', 'intent', 'entities', 'expanded_terms',
        'method', 'result_count', 'top_score', 'duration_ms',
    ];

    protected $casts = [
        'entities' => 'array',
        'expanded_terms' => 'array',
        'result_count' => 'integer',
        'top_score' => 'float',
        'duration_ms' => 'float',
    ];
}