<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDocument extends Model
{
    protected $table = 'ai_documents';

    protected $fillable = [
        'knowledge_id', 'category', 'source_type', 'source_id',
        'term_count', 'unique_term_count',
    ];
}