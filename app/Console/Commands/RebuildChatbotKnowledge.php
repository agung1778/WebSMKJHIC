<?php

namespace App\Console\Commands;

use App\Services\KnowledgeBaseService;
use Illuminate\Console\Command;

class RebuildChatbotKnowledge extends Command
{
    protected $signature = 'chatbot:rebuild-knowledge';

    protected $description = 'Membangun ulang knowledge base chatbot Tanya AI dari data website';

    public function handle(KnowledgeBaseService $knowledgeBase): int
    {
        $count = $knowledgeBase->rebuild();

        $this->info("Knowledge base berhasil dibangun ulang: {$count} chunk.");

        return self::SUCCESS;
    }
}
