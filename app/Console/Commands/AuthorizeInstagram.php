<?php

namespace App\Console\Commands;

use App\Services\InstagramFeedSyncService;
use Illuminate\Console\Command;
use Throwable;

class AuthorizeInstagram extends Command
{
    protected $signature = 'instagram:authorize';

    protected $description = 'Hubungkan akun Instagram sekali kali untuk mendapatkan token Graph API';

    public function handle(InstagramFeedSyncService $sync): int
    {
        $state = bin2hex(random_bytes(16));

        try {
            $url = $sync->authorizationUrl($state);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Buka URL berikut di browser dan authorize akun Instagram yang terhubung ke Facebook Page:');
        $this->line($url);
        $this->newLine();
        $this->comment('Setelah di-authorize, salin nilai parameter "code" dari alamat URL hasil redirect.');
        $this->comment('(State untuk referensi: '.$state.')');
        $this->newLine();

        $code = trim((string) $this->ask('Tempel kode otorisasi di sini'));
        if ($code === '') {
            $this->error('Kode otorisasi kosong.');

            return self::FAILURE;
        }

        try {
            $tokens = $sync->exchangeAuthorizationCode($code);
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Gagal menghubungkan Instagram: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Berhasil. Token tersimpan di storage/app/private/instagram-oauth.json');
        $this->line('Account ID: '.($tokens['account_id'] ?? '-'));
        $this->line('Username: '.($tokens['username'] ?: '-'));
        $this->line('Jalankan: php artisan instagram:sync');

        return self::SUCCESS;
    }
}
