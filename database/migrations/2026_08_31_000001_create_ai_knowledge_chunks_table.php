<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_knowledge_chunks', function (Blueprint $table) {
            $table->id();
            // Jenis sumber data: major, spmb, facility, extracurricular, news, teacher,
            // partner, testimonial, program, achievement, about
            $table->string('source_type')->index();
            $table->unsignedBigInteger('source_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('content');
            // Embedding disimpan sebagai JSON (array numerik) opsional.
            $table->json('embedding')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge_chunks');
    }
};
