<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolLeader extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school',
        'name',
        'position',
        'quote',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'image',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}