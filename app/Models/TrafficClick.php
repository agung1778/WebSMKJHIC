<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_token',
        'click_type',
        'element_name',
        'element_url',
        'page_url',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'os',
        'country',
        'referrer',
        'clicked_at',
    ];

    protected $dates = ['clicked_at'];
}