<?php

namespace App\Console\Commands;

use App\Services\InstagramFeedSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncInstagramFeed extends Command
{
    protected $signature = 'instagram:sync';

    protected $description = 'Sinkronkan postingan Instagram terbaru ke website';

    public function handle(InstagramFeedSyncService $sync): int
    {
        try {
            $count = $sync->sync();
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Sinkronisasi Instagram gagal. Periksa konfigurasi dan log aplikasi.');

            return self::FAILURE;
        }

        $this->info("{$count} postingan Instagram disinkronkan.");

        return self::SUCCESS;
    }
}