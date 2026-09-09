<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkk_projects', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel majors (jurusan)
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');

            $table->string('title'); // Nama Produk/Project
            $table->string('student_names'); // Nama Siswa/Kelompok
            $table->string('student_class'); // Kelas (X, XI, XII)
            $table->text('description'); // Deskripsi produk
            $table->string('photo'); // Gambar produk
            $table->string('category'); // Makanan, Kerajinan, Jasa, Teknologi
            $table->decimal('price', 15, 2)->nullable(); // Harga (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkk_projects');
    }
};
