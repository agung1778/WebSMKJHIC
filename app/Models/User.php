<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- TAMBAHKAN INI

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'status',
        'password',
        'role',
        'last_login_at',
        'last_login_ip',
        'last_login_browser',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Mendefinisikan relasi one-to-one ke sesi terakhir pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session(): HasOne
    {
        return $this->hasOne(\App\Models\Session::class)->latestOfMany();
    }

    /**
     * Ambil info browser dari user_agent string.
     */
    public function getBrowserAttribute(): string
    {
        $ua = optional($this->session)->user_agent ?? '';
        if (str_contains($ua, 'Edg/')) return 'Microsoft Edge';
        if (str_contains($ua, 'Chrome') && !str_contains($ua, 'Edg/')) return 'Google Chrome';
        if (str_contains($ua, 'Firefox')) return 'Mozilla Firefox';
        if (str_contains($ua, 'Safari') && !str_contains($ua, 'Chrome')) return 'Safari';
        return 'Unknown Browser';
    }
}
