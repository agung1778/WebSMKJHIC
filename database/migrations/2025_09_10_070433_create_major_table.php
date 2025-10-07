<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('tag')->nullable();         // BARU: Tag atau kata kunci
            $table->text('advantage')->nullable();   // BARU: Poin-poin keunggulan
            $table->string('image')->nullable();
            $table->string('logo')->nullable();
            $table->string('competency_head');
            $table->string('competency_head_photo')->nullable();
            $table->string('publisher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
