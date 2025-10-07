<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'alumni_year',
        'major_id',
        'description',
        'photo',
        'publisher',
    ];

    /**
     * Get the major that the testimonial belongs to.
     */
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}