<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_token',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'os',
        'country',
        'referrer',
        'page_url',
        'visited_at',
    ];

    protected $dates = ['visited_at'];
}