<?php

namespace App\Console\Commands;

use App\AI\Core\AIEngine;
use App\AI\Knowledge\KnowledgeIndexer;
use App\AI\Support\AIConfig;
use Illuminate\Console\Command;

class AIIndexCommand extends Command
{
    protected $signature = 'ai:index
                            {--force : Paksa rebuild penuh (bukan incremental)}
                            {--no-relationships : Jangan bangun graf relasi}
                            {--compact : Output ringkas tanpa tabel}';

    protected $description = 'Membangun ulang atau memperbarui index knowledge AI (lokal, read-only).';

    public function handle(KnowledgeIndexer $indexer): int
    {
        $force = (bool) $this->option('force');
        $withRelations = ! (bool) $this->option('no-relationships');
        $compact = (bool) $this->option('compact');

        $start = microtime(true);

        if (! $compact) {
            $this->info('Memulai indexing knowledge...');
        }

        $stats = $indexer->rebuild(incremental: ! $force, withRelationships: $withRelations);

        $duration = round((microtime(true) - $start) * 1000, 2);

        if ($compact) {
            return self::SUCCESS;
        }

        $this->table(
            ['Inserted', 'Updated', 'Unchanged', 'Deleted', 'Relationships', 'Errors', 'Duration (ms)'],
            [[
                $stats['inserted'],
                $stats['updated'],
                $stats['unchanged'],
                $stats['deleted'],
                $stats['relationships'],
                $stats['errors'] ? implode(', ', $stats['errors']) : '-',
                $duration,
            ]]
        );

        $this->info('Index berhasil dibangun.');

        return self::SUCCESS;
    }
}