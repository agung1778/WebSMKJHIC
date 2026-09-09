<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstaPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'path',
        'post_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
