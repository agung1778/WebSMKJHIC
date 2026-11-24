<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiktokPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'path',
        'video_url',
        'video_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
