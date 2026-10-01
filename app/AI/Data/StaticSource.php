<?php

namespace App\AI\Data;

use App\AI\Knowledge\KnowledgeRecord;

/**
 * Sumber data statis yang dikonfigurasi langsung di config, misalnya identitas
 * sekolah, kontak, dan profil singkat. Tidak memerlukan query database.
 */
class StaticSource implements KnowledgeSource
{
    protected array $records = [];

    public function __construct(array $items = [])
    {
        $this->records = $items ?: self::defaults();
    }

    public static function defaults(): array
    {
        $identity = (array) config('ai.identity', []);

        return [
            KnowledgeRecord::make('school_identity', 'identity', 'Profil Sekolah', self::profileText($identity))
                ->withSourceUrl((string) ($identity['website'] ?? '/'))
                ->withKeywords([
                    'profil sekolah', 'tentang sekolah', 'sekolah', 'nama sekolah',
                    'lokasi', 'alamat sekolah', 'dimana sekolah', 'alamat',
                    'profil', 'sekolah amaliah',
                ])
                ->withMetadata(['kind' => 'identity']),

            KnowledgeRecord::make('contact', 'contact', 'Kontak & Alamat Sekolah', self::contactText($identity))
                ->withSourceUrl((string) ($identity['website'] ?? '/'))
                ->withKeywords([
                    'kontak', 'telepon', 'nomor telepon', 'wa', 'whatsapp',
                    'email', 'hubungi', 'alamat', 'lokasi', 'sosial media',
                    'instagram', 'youtube', 'website',
                ])
                ->withMetadata(['kind' => 'contact']),
        ];
    }

    protected static function profileText(array $identity): string
    {
        return trim(
            ($identity['name'] ?? 'Sekolah') . "\n"
            . 'Alamat: ' . ($identity['address'] ?? '-') . "\n"
            . 'Telepon: ' . ($identity['phone'] ?? '-') . "\n"
            . 'Email: ' . ($identity['email'] ?? '-')
        );
    }

    protected static function contactText(array $identity): string
    {
        $wa = (string) ($identity['whatsapp'] ?? '');

        return trim(
            'Telepon: ' . ($identity['phone'] ?? '-') . "\n"
            . 'WhatsApp: ' . ($wa !== '' ? 'https://wa.me/' . $wa : '-') . "\n"
            . 'Email: ' . ($identity['email'] ?? '-') . "\n"
            . 'Instagram: ' . ($identity['instagram'] ?? '-') . "\n"
            . 'YouTube: ' . ($identity['youtube'] ?? '-') . "\n"
            . 'Website: ' . ($identity['website'] ?? '-')
        );
    }

    public function fetch(): array
    {
        return $this->records;
    }
}