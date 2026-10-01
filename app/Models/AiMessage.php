<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMessage extends Model
{
    protected $table = 'ai_messages';

    protected $fillable = ['conversation_id', 'role', 'message', 'meta'];

    protected $casts = ['meta' => 'array'];
}