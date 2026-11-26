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

    /**
     * Dapatkan path fisik file logo di filesystem.
     */
    public function getLogoFullPath(): ?string
    {
        if (empty($this->logo)) {
            return null;
        }

        // Cek disk public storage
        $storagePath = \Illuminate\Support\Facades\Storage::disk('public')->path($this->logo);
        if (is_file($storagePath)) {
            return $storagePath;
        }

        // Cek path langsung di public/
        $publicPath = public_path($this->logo);
        if (is_file($publicPath)) {
            return $publicPath;
        }

        // Cek jika path berawalan atau berada di public/storage/
        $storagePublicPath = public_path('storage/' . ltrim($this->logo, '/'));
        if (is_file($storagePublicPath)) {
            return $storagePublicPath;
        }

        return null;
    }

    /**
     * Cek apakah partner memiliki logo yang valid dan tersedia di disk.
     */
    public function hasValidLogo(): bool
    {
        return $this->getLogoFullPath() !== null;
    }

    /**
     * Dapatkan ekstensi file logo.
     */
    public function getLogoExtensionAttribute(): string
    {
        $path = $this->getLogoFullPath();
        return $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : 'png';
    }

    /**
     * Dapatkan ukuran file logo dalam byte.
     */
    public function getLogoSizeAttribute(): ?int
    {
        $path = $this->getLogoFullPath();
        return $path && is_file($path) ? @filesize($path) : null;
    }

    /**
     * Dapatkan ukuran terformat (KB / MB).
     */
    public function getFormattedLogoSizeAttribute(): string
    {
        $size = $this->logo_size;
        if (!$size) {
            return '-';
        }

        return $size >= 1048576
            ? number_format($size / 1048576, 1, ',', '.') . ' MB'
            : number_format($size / 1024, 1, ',', '.') . ' KB';
    }

    /**
     * Dapatkan dimensi gambar logo (misal: "800 x 600").
     */
    public function getLogoDimensionsAttribute(): ?string
    {
        $path = $this->getLogoFullPath();
        if (!$path || !is_file($path)) {
            return null;
        }

        $info = @getimagesize($path);
        return $info ? ($info[0] . ' x ' . $info[1]) : null;
    }
}