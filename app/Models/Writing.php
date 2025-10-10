<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'publisher',
        'release_date',
    ];

    /**
     * Tentukan field yang harus di-cast ke tipe data tertentu.
     */
    protected $casts = [
        'release_date' => 'datetime', // BARU: Mengkonversi string tanggal menjadi objek Carbon
    ];
}