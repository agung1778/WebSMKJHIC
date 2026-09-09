<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal.
     * Pastikan 'title' dan kolom lainnya ada di dalam array ini.
     */
    protected $fillable = [
        'title',
        'url',
        'position',
        'type',
        'target',
        'order',
        'is_active',
    ];
}
