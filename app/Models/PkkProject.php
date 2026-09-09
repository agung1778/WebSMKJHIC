<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PkkProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'major_id',
        'title',
        'brand_name',     // Baru
        'student_names',
        'student_class',
        'description',
        'photo',
        'logo',           // Baru
        'category',
        'price',
        'contact_info',   // Baru
        'social_media',   // Baru
    ];

    /**
     * Relasi kebalikan: Project milik satu Jurusan
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
