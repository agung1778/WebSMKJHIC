<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiKeyword extends Model
{
    protected $table = 'ai_keywords';

    protected $fillable = ['token', 'document_frequency', 'is_stopword'];

    protected $casts = ['is_stopword' => 'boolean'];
}