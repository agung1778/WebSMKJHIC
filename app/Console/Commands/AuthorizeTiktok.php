<?php

namespace App\Console\Commands;

use App\Services\TiktokFeedSyncService;
use Illuminate\Console\Command;
use Throwable;

class AuthorizeTiktok extends Command
{
    protected $signature = 'tiktok:authorize';

    protected $description = 'Hubungkan akun TikTok sekali kali untuk mendapatkan token Display API';

    public function handle(TiktokFeedSyncService $sync): int
    {
        $state = bin2hex(random_bytes(16));

        try {
            $url = $sync->authorizationUrl($state);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Buka URL berikut di browser dan authorize akun @smkamaliah:');
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
            $this->error('Gagal menghubungkan TikTok: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Berhasil. Token tersimpan di storage/app/private/tiktok-oauth.json');
        $this->line('Open ID: '.($tokens['open_id'] ?? '-'));
        $this->line('Jalankan: php artisan tiktok:sync');

        return self::SUCCESS;
    }
}
