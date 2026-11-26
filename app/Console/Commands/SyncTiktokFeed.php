<?php

namespace App\Console\Commands;

use App\Services\TiktokFeedSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncTiktokFeed extends Command
{
    protected $signature = 'tiktok:sync';

    protected $description = 'Sinkronkan video TikTok terbaru ke website';

    public function handle(TiktokFeedSyncService $sync): int
    {
        if (!$sync->isConfigured()) {
            $this->warn('TikTok Display API belum dikonfigurasi. Sinkronisasi dilewati.');

            return self::SUCCESS;
        }

        try {
            $count = $sync->sync();
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Sinkronisasi TikTok gagal. Periksa konfigurasi dan log aplikasi.');

            return self::FAILURE;
        }

        $this->info("{$count} video TikTok disinkronkan.");

        return self::SUCCESS;
    }
}
