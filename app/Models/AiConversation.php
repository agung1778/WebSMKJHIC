<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    protected $table = 'ai_conversations';

    protected $fillable = [
        'session_id', 'last_intent', 'last_entities',
        'last_source_type', 'last_knowledge_id', 'context', 'turn_count',
    ];

    protected $casts = [
        'last_entities' => 'array',
        'context'       => 'array',
        'turn_count'    => 'integer',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class, 'conversation_id');
    }
}