<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledgeChunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_type',
        'source_id',
        'title',
        'content',
        'embedding',
    ];

    protected $casts = [
        'embedding' => 'array',
    ];

    public const SOURCES = [
        'major', 'spmb', 'facility', 'extracurricular', 'news',
        'teacher', 'partner', 'testimonial', 'program', 'achievement', 'about',
    ];
}
