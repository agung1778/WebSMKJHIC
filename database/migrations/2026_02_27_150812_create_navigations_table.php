<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Teks yang tampil (ex: "Info SPMB")
            $table->string('url'); // Link tujuan
            $table->string('position')->default('top_bar'); // Lokasi menu: top_bar, main_menu, footer
            $table->string('type')->default('link'); // Bentuk visual: 'link' (teks biasa) atau 'button' (tombol hitam)
            $table->string('target')->default('_self'); // Buka di tab sama (_self) atau tab baru (_blank)
            $table->integer('order')->default(0); // Untuk urutan menu
            $table->boolean('is_active')->default(true); // Fitur sembunyikan menu tanpa menghapus data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
