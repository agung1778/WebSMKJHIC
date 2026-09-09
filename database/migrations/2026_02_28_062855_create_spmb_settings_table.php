<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Buka', 'Tutup'])->default('Buka');
            $table->string('wave_name')->nullable(); // cth: Gelombang Inden Dibuka!
            $table->string('period_date')->nullable(); // cth: 1 Oktober - 4 Januari
            $table->string('quota_note')->nullable(); // cth: *Kuota Terbatas

            // Kolom untuk path gambar brosur
            $table->string('brochure_image_1')->nullable();
            $table->string('brochure_image_2')->nullable();
            $table->string('brochure_full_image')->nullable();

            // Kolom untuk file download PDF
            $table->string('brochure_file')->nullable();

            // Link ke web PPDB pendaftaran
            $table->string('registration_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_settings');
    }
};
