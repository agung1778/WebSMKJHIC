<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    /**
     * Daftar sektor/bidang usaha mitra yang disediakan di form admin.
     *
     * @var array<int, string>
     */
    public const SECTOR_OPTIONS = [
        'Jasa Hiburan & Wisata',
        'Pariwisata & Perhotelan',
        'Teknologi Informasi & Digital',
        'Kuliner & Makanan',
        'Manufaktur & Industri',
        'Pendidikan & Pelatihan',
        'Kesehatan & Farmasi',
        'Perbankan & Keuangan',
        'Media, Kreatif & Pemasaran',
        'Ritel & Perdagangan',
        'Logistik & Transportasi',
        'Konstruksi & Properti',
        'Pertanian & Agribisnis',
        'Otomotif',
        'Energi & Utilitas',
        'Lainnya',
    ];

    /**
     * Opsi sektor dengan nilai yang sama (untuk dropdown).
     *
     * @return array<string, string>
     */
    public static function sectorOptions(): array
    {
        return array_combine(self::SECTOR_OPTIONS, self::SECTOR_OPTIONS);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
        'sector',
        'city',
        'company_contact',
        'publisher',
        'partnership_date',
    ];
}