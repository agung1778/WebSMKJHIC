<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiIntent extends Model
{
    protected $table = 'ai_intents';

    protected $fillable = [
        'name', 'description', 'patterns', 'response_template',
        'category', 'is_active',
    ];

    protected $casts = [
        'patterns' => 'array',
        'response_template' => 'array',
        'is_active' => 'boolean',
    ];
}