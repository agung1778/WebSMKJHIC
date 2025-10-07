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
        Schema::create('image', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();      // Judul Gambar (Baru)
            $table->text('description')->nullable(); // Deskripsi Gambar (Baru)
            $table->string('filename');             // Nama asli file
            $table->string('path');                 // Jalur penyimpanan di storage
            $table->string('mime_type')->nullable(); // Tipe file
            $table->unsignedBigInteger('size')->nullable(); // Ukuran file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image');
    }
};
