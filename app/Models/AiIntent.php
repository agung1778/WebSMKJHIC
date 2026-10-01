<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiIntent extends Model
{
    protected $table = 'ai_intents';

    protected $fillable = [
        'name', 'label', 'keywords', 'patterns',
        'answer_templates', 'source_types', 'priority', 'is_fallback',
    ];

    protected $casts = [
        'keywords'          => 'array',
        'patterns'          => 'array',
        'answer_templates'  => 'array',
        'is_fallback'       => 'boolean',
    ];

    /** Daftar source_type yang diizinkan untuk intent ini. */
    public function sourceTypeList(): array
    {
        if (empty($this->source_types)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->source_types))));
    }
}