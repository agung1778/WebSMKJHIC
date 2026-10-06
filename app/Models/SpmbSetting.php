<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmbSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'is_active',
        'popup_enabled',
        'popup_frequency',
        'popup_theme',
        'popup_delay',
        'popup_size',
        'popup_position',
        'popup_show_image',
        'popup_show_detail_button',
        'popup_subtitle',
        'popup_title',
        'popup_badge',
        'popup_button_text',
        'popup_logo',
        'popup_image',
        'wave_name',
        'wave_category',
        'wave_description',
        'period_date',
        'quota_note',
        'brochure_image_1',
        'brochure_image_2',
        'brochure_full_image',
        'brochure_file',
        'registration_link',
    ];

    protected $attributes = [
        'popup_frequency' => 'always',
        'popup_theme' => 'hijau',
        'popup_size' => 'sedang',
        'popup_position' => 'tengah',
        'popup_show_image' => true,
        'popup_show_detail_button' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'popup_enabled' => 'boolean',
        'popup_delay' => 'integer',
        'popup_show_image' => 'boolean',
        'popup_show_detail_button' => 'boolean',
    ];

    /**
     * Opsi frekuensi popup untuk form admin.
     */
    public static function popupFrequencies(): array
    {
        return [
            'always' => 'Setiap halaman & refresh (paling disarankan)',
            'session' => 'Sekali per sesi browser (sembunyi sampai tab ditutup)',
            'daily' => 'Sekali per hari',
            'once' => 'Hanya sekali sampai data diubah',
        ];
    }

    /**
     * Opsi ukuran (lebar) kartu popup.
     */
    public static function popupSizes(): array
    {
        return [
            'kecil' => 'Kecil (420px)',
            'sedang' => 'Sedang (560px)',
            'besar' => 'Besar (720px)',
        ];
    }

    /**
     * Opsi posisi popup di layar.
     */
    public static function popupPositions(): array
    {
        return [
            'atas' => 'Atas',
            'tengah' => 'Tengah',
            'bawah' => 'Bawah',
        ];
    }

    /**
     * Opsi tema/gradasi banner popup.
     */
    public static function popupThemes(): array
    {
        return [
            'hijau' => 'Hijau Amaliah',
            'gelap' => 'Gelap',
            'biru' => 'Biru',
            'ungu' => 'Ungu',
            'jingga' => 'Jingga',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Data yang dipakai halaman publik: record aktif, atau record terbaru
     * bila belum ada yang ditandai aktif.
     */
    public static function active(): ?self
    {
        return static::query()->active()->orderByDesc('id')->first()
            ?? static::query()->orderByDesc('id')->first();
    }
}