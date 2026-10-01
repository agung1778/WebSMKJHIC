<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiKeyword extends Model
{
    protected $table = 'ai_keywords';

    protected $fillable = ['token', 'knowledge_id', 'document_id', 'field', 'term_frequency'];

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(AiKnowledge::class, 'knowledge_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(AiDocument::class, 'document_id');
    }
}