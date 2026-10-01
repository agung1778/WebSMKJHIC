<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRelationship extends Model
{
    protected $table = 'ai_relationships';

    protected $fillable = ['source_knowledge_id', 'target_knowledge_id', 'relation_type', 'weight'];

    public function source(): BelongsTo
    {
        return $this->belongsTo(AiKnowledge::class, 'source_knowledge_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(AiKnowledge::class, 'target_knowledge_id');
    }
}