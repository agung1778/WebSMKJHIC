<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'photo',
        'position',
        'subject',
        'additional_duties',
        'school',
        'category',
        'order_column',
    ];

    /**
     * Scope to order teachers by hierarchy rank, then ID.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get duties as an array.
     */
    public function getDutiesListAttribute(): array
    {
        if (empty($this->additional_duties)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->additional_duties))));
    }
}
