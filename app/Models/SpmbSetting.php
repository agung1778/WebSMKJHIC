<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmbSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'wave_name',
        'period_date',
        'quota_note',
        'brochure_image_1',
        'brochure_image_2',
        'brochure_full_image',
        'brochure_file',
        'registration_link',
    ];
}
