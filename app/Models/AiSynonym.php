<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSynonym extends Model
{
    protected $table = 'ai_synonyms';

    protected $fillable = ['term', 'synonyms', 'group_key'];

    protected $casts = [
        'synonyms' => 'array',
    ];
}