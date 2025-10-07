<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    // Definisikan nama tabel singular
    protected $table = 'image'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',          // BARU
        'description',    // BARU
        'filename',
        'path',
        'mime_type',
        'size',
    ];
}