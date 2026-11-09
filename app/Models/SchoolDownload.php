<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolDownload extends Model
{
    // Retain the existing table and records when upgrading the original logo module.
    protected $table = 'logo_downloads';

    protected $fillable = [
        'name', 'description', 'file_path', 'original_name', 'mime_type', 'extension',
        'file_size', 'width', 'height', 'is_active', 'dark_preview', 'sort_order', 'uploaded_by', 'category', 'major_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dark_preview' => 'boolean',
        'sort_order' => 'integer',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'major_id' => 'integer',
    ];

    public static function categories(): array
    {
        return [
            'logo' => 'Logo Sekolah',
            'company_logo' => 'Logo Perusahaan',
            'pkl' => 'Contoh Laporan PKL',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->category] ?? 'Unduhan';
    }

    public static function formatsFor(string $category): array
    {
        return match ($category) {
            'logo', 'company_logo' => ['png', 'jpg', 'jpeg', 'webp', 'pdf'],
            'pkl' => ['pdf', 'docx'],
            default => [],
        };
    }

    public function getDocumentIconAttribute(): string
    {
        return $this->extension === 'docx' ? 'fa-file-word' : 'fa-file-pdf';
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function getMajorLabelAttribute(): ?string
    {
        return $this->category === 'pkl' ? ($this->major?->name ?? 'Umum / Semua Jurusan') : null;
    }

    public function scopeForMajor(Builder $query, ?string $major): Builder
    {
        if ($major === null) {
            return $query;
        }

        return $query->where('category', 'pkl')->when(
            $major === 'umum',
            fn (Builder $query) => $query->whereNull('major_id'),
            fn (Builder $query) => $query->where('major_id', (int) $major),
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->mime_type, ['image/png', 'image/jpeg', 'image/webp'], true);
    }

    public function getFormattedSizeAttribute(): string
    {
        return $this->file_size >= 1048576
            ? number_format($this->file_size / 1048576, 1, ',', '.').' MB'
            : number_format($this->file_size / 1024, 1, ',', '.').' KB';
    }

    public function getDimensionsAttribute(): ?string
    {
        return $this->width && $this->height ? $this->width.' × '.$this->height.' px' : null;
    }
}
