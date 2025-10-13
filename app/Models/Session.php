<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // Tidak perlu $fillable karena kita hanya akan membaca data dari tabel ini
    protected $table = 'sessions'; // Menentukan nama tabel secara eksplisit

    // Memberitahu Laravel untuk tidak menggunakan created_at dan updated_at
    public $timestamps = false;
}