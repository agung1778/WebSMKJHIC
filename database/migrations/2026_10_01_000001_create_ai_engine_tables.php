<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel inti Custom AI Information Engine.
 *
 * ai_knowledge        : satu baris = satu knowledge record dari sumber data website
 * ai_documents        : breakdown per-field (title/body) + vektor TF-IDF untuk pencarian
 * ai_keywords         : inverted index token -> knowledge_id + tf
 * ai_relationships    : knowledge graph sederhana antar record
 * ai_intents          : definisi intent + keyword + template jawaban
 * ai_synonyms         : kamus sinonim Bahasa Indonesia (bisa ditambah admin)
 * ai_conversations    : sesi percakapan (memory)
 * ai_messages         : pesan user & jawaban AI dalam percakapan
 * ai_search_logs      : log debugging/analitik pencarian
 * ai_index_runs       : riwayat proses indexing
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 100)->unique();          // mis. major-001, news-42
            $table->string('source_type')->index();        // major, teacher, news, ...
            $table->unsignedBigInteger('source_id')->nullable()->index();
            $table->string('title');
            $table->longText('content');
            $table->string('category')->nullable()->index();
            $table->string('url')->nullable();
            $table->string('checksum', 64)->index();        // deteksi perubahan (incremental index)
            $table->timestamp('indexed_at')->nullable();
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
        });

        Schema::create('ai_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_id')->constrained('ai_knowledge')->cascadeOnDelete();
            $table->string('field')->default('body');     // title, body, meta
            $table->longText('content');
            $table->unsignedInteger('length')->default(0); // jumlah token
            $table->timestamps();

            $table->index(['knowledge_id', 'field']);
        });

        Schema::create('ai_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->index();           // token hasil normalisasi+stemming
            $table->foreignId('knowledge_id')->constrained('ai_knowledge')->cascadeOnDelete();
            $table->foreignId('document_id')->nullable()->constrained('ai_documents')->cascadeOnDelete();
            $table->string('field')->default('body');      // di token ini muncul: title/body
            $table->unsignedInteger('term_frequency')->default(1);
            $table->timestamps();

            $table->index(['token', 'knowledge_id']);
        });

        Schema::create('ai_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_knowledge_id')->constrained('ai_knowledge')->cascadeOnDelete();
            $table->foreignId('target_knowledge_id')->constrained('ai_knowledge')->cascadeOnDelete();
            $table->string('relation_type')->index();      // has_major, has_teacher, ...
            $table->unsignedTinyInteger('weight')->default(1);
            $table->timestamps();

            $table->unique(['source_knowledge_id', 'target_knowledge_id', 'relation_type'], 'ai_rel_unique');
            $table->index(['relation_type', 'source_knowledge_id']);
        });

        Schema::create('ai_intents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();          // PROGRAM_KEAHLIAN, PPDB, ...
            $table->string('label');
            $table->text('keywords')->nullable();          // JSON array
            $table->text('patterns')->nullable();          // JSON array regex sederhana
            $table->text('answer_templates')->nullable();  // JSON array (variasi kalimat)
            $table->string('source_types')->nullable();    // CSV: major,news,...
            $table->unsignedTinyInteger('priority')->default(50);
            $table->boolean('is_fallback')->default(false);
            $table->timestamps();
        });

        Schema::create('ai_synonyms', function (Blueprint $table) {
            $table->id();
            $table->string('term', 120)->index();          // kata/frasa pada pertanyaan user
            $table->text('synonyms');                      // JSON array bentuk kanonik
            $table->string('group_key', 120)->nullable()->index();
            $table->timestamps();

            $table->unique(['term', 'group_key']);
        });

        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 80)->index();
            $table->string('last_intent', 80)->nullable();
            $table->text('last_entities')->nullable();     // JSON
            $table->string('last_source_type', 60)->nullable();
            $table->unsignedBigInteger('last_knowledge_id')->nullable();
            $table->text('context')->nullable();            // JSON: {"subject":"IPAS","major":"RPL"}
            $table->unsignedSmallInteger('turn_count')->default(0);
            $table->timestamps();
        });

        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('ai_conversations')->cascadeOnDelete();
            $table->string('role', 10);                     // user | assistant
            $table->text('message');
            $table->string('intent', 80)->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });

        Schema::create('ai_search_logs', function (Blueprint $table) {
            $table->id();
            $table->text('query')->nullable();
            $table->string('normalized_query', 255)->nullable();
            $table->string('intent', 80)->nullable();
            $table->text('entities')->nullable();
            $table->text('keywords')->nullable();
            $table->text('matched')->nullable();            // JSON [{id,title,score}]
            $table->decimal('confidence', 5, 4)->nullable();
            $table->boolean('answered')->default(false);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index(['intent', 'created_at']);
            $table->index('answered');
        });

        Schema::create('ai_index_runs', function (Blueprint $table) {
            $table->id();
            $table->string('source_type')->nullable()->index();
            $table->string('mode', 20)->default('full');   // full | incremental
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('indexed')->default(0);
            $table->unsignedInteger('skipped')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->unsignedInteger('removed')->default(0);
            $table->text('errors')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_index_runs');
        Schema::dropIfExists('ai_search_logs');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ai_synonyms');
        Schema::dropIfExists('ai_intents');
        Schema::dropIfExists('ai_relationships');
        Schema::dropIfExists('ai_keywords');
        Schema::dropIfExists('ai_documents');
        Schema::dropIfExists('ai_knowledge');
    }
};