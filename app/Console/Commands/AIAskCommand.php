<?php

namespace App\Console\Commands;

use App\AI\Core\AIEngine;
use Illuminate\Console\Command;

class AIAskCommand extends Command
{
    protected $signature = 'ai:ask {question* : Pertanyaan yang akan diajukan ke AI}';

    protected $description = 'Uji coba AI Chatbot lokal (tidak memanggil API eksternal).';

    public function handle(AIEngine $engine): int
    {
        $question = implode(' ', $this->argument('question'));

        if (trim($question) === '') {
            $this->error('Pertanyaan tidak boleh kosong.');
            return self::FAILURE;
        }

        $response = $engine->ask($question);

        if (! $response['success']) {
            $this->error($response['answer']);
            return self::FAILURE;
        }

        $this->info('Jawaban:');
        $this->line($response['answer']);

        $this->newLine();
        $this->comment(sprintf(
            'Intent: %s | Confidence: %.4f | TopScore: %.4f | Sources: %d',
            $response['intent'],
            $response['confidence'],
            $response['top_score'],
            count($response['sources'])
        ));

        if (! empty($response['sources'])) {
            $this->table(
                ['Kategori', 'Judul', 'URL', 'Score', 'Conf'],
                array_map(static fn ($s) => [
                    $s['category'],
                    $s['title'],
                    $s['url'] ?? '-',
                    $s['score'],
                    $s['confidence'],
                ], $response['sources'])
            );
        }

        return self::SUCCESS;
    }
}