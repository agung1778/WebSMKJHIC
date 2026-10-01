<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---- ai_knowledge: satu baris = satu fakta/atribut Atomic -------
        if (! Schema::hasTable('ai_knowledge')) {
            Schema::create('ai_knowledge', function (Blueprint $table) {
                $table->id();
                $table->string('category')->index();          // major, teacher, news, ...
                $table->string('source_type')->index();       // database, website, document
                $table->string('source_id')->nullable()->index();
                $table->string('title')->nullable();
                $table->text('content');
                $table->string('source_url')->nullable();
                $table->json('keywords')->nullable();
                $table->json('metadata')->nullable();
                $table->string('checksum', 64)->index();       // untuk incremental index
                $table->timestamp('indexed_at')->nullable();
                $table->timestamps();

                $table->index(['category', 'source_id'], 'ai_knowledge_category_source_idx');
            });
        }

        // ---- ai_keywords: kamus token + DF untuk TF-IDF -------------------
        if (! Schema::hasTable('ai_keywords')) {
            Schema::create('ai_keywords', function (Blueprint $table) {
                $table->id();
                $table->string('token')->unique();
                $table->unsignedInteger('document_frequency')->default(0);
                $table->boolean('is_stopword')->default(false);
                $table->timestamps();
            });
        }

        // ---- ai_documents: statistik per dokumen untuk BM25 ---------------
        if (! Schema::hasTable('ai_documents')) {
            Schema::create('ai_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('knowledge_id')->nullable()->index();
                $table->string('category')->index();
                $table->string('source_type')->index();
                $table->string('source_id')->nullable()->index();
                $table->unsignedInteger('term_count')->default(0);
                $table->unsignedInteger('unique_term_count')->default(0);
                $table->timestamps();
            });
        }

        // ---- ai_relationships: graf relasi antar fakta --------------------
        if (! Schema::hasTable('ai_relationships')) {
            Schema::create('ai_relationships', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('from_id')->index();
                $table->unsignedBigInteger('to_id')->index();
                $table->string('relation')->default('related_to');
                $table->float('weight')->default(1.0);
                $table->timestamps();

                $table->unique(['from_id', 'to_id', 'relation'], 'ai_rel_unique');
            });
        }

        // ---- ai_conversations: sesi percakapan ----------------------------
        if (! Schema::hasTable('ai_conversations')) {
            Schema::create('ai_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 100)->index();
                $table->timestamps();
            });
        }

        // ---- ai_messages: riwayat percakapan ------------------------------
        if (! Schema::hasTable('ai_messages')) {
            Schema::create('ai_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id')->nullable()->index();
                $table->enum('role', ['user', 'assistant'])->default('user');
                $table->text('message');
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['conversation_id', 'created_at'], 'ai_msg_conv_created_idx');
            });
        }

        // ---- ai_intents: definisi intent ----------------------------------
        if (! Schema::hasTable('ai_intents')) {
            Schema::create('ai_intents', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();               // greeting, major_info, ...
                $table->text('description')->nullable();
                $table->json('patterns')->nullable();           // regex/kata kunci pemicu
                $table->json('response_template')->nullable();   // templat jawaban
                $table->string('category')->nullable()->index();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ---- ai_synonyms: kamus sinonim & alias ---------------------------
        if (! Schema::hasTable('ai_synonyms')) {
            Schema::create('ai_synonyms', function (Blueprint $table) {
                $table->id();
                $table->string('term')->index();
                $table->string('synonym')->index();
                $table->float('weight')->default(1.0);
                $table->timestamps();

                $table->unique(['term', 'synonym'], 'ai_syn_unique');
            });
        }

        // ---- ai_search_logs: audit/debug pencarian ------------------------
        if (! Schema::hasTable('ai_search_logs')) {
            Schema::create('ai_search_logs', function (Blueprint $table) {
                $table->id();
                $table->text('question');
                $table->string('intent')->nullable();
                $table->json('entities')->nullable();
                $table->json('expanded_terms')->nullable();
                $table->string('method')->nullable();           // keyword/tfidf/bm25/hybrid
                $table->unsignedInteger('result_count')->default(0);
                $table->float('top_score')->default(0);
                $table->float('duration_ms')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'ai_search_logs', 'ai_synonyms', 'ai_intents', 'ai_messages',
            'ai_conversations', 'ai_relationships', 'ai_documents',
            'ai_keywords', 'ai_knowledge',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};