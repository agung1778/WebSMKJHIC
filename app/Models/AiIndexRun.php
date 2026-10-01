<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiIndexRun extends Model
{
    protected $table = 'ai_index_runs';

    protected $fillable = [
        'source_type', 'mode', 'total', 'indexed', 'skipped',
        'failed', 'removed', 'errors', 'duration_ms',
    ];

    protected $casts = [
        'errors'      => 'array',
        'duration_ms' => 'integer',
    ];
}