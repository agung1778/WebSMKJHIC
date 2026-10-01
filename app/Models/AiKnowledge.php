<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiKnowledge extends Model
{
    protected $table = 'ai_knowledge';

    protected $fillable = [
        'uid', 'source_type', 'source_id', 'title',
        'content', 'category', 'url', 'checksum', 'indexed_at',
    ];

    protected $casts = [
        'indexed_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(AiDocument::class, 'knowledge_id');
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(AiKeyword::class, 'knowledge_id');
    }

    public function outRelationships(): HasMany
    {
        return $this->hasMany(AiRelationship::class, 'source_knowledge_id');
    }

    public function inRelationships(): HasMany
    {
        return $this->hasMany(AiRelationship::class, 'target_knowledge_id');
    }
}