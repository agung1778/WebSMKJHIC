<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiDocument extends Model
{
    protected $table = 'ai_documents';

    protected $fillable = ['knowledge_id', 'field', 'content', 'length'];

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(AiKnowledge::class, 'knowledge_id');
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(AiKeyword::class, 'document_id');
    }
}