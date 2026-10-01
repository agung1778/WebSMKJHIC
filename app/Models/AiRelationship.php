<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRelationship extends Model
{
    protected $table = 'ai_relationships';

    protected $fillable = ['from_id', 'to_id', 'relation', 'weight'];

    protected $casts = ['weight' => 'float'];
}