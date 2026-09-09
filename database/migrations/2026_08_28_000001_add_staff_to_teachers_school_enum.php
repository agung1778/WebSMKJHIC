<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan nilai 'Staff' pada kolom enum `school` tabel `teachers`.
     *
     * - Kolom yang diubah : `school` (enum)
     * - Nilai baru         : 'Staff'
     * - Alasan             : Admin perlu membedakan staf yang tidak termasuk
     *                         kategori Amaliah 1 maupun Amaliah 2.
     * - Dampak ke data lama: Semua nilai lama (Amaliah 1, Amaliah 2,
     *                         Amaliah 1 & 2) tetap aman; hanya menambahkan
     *                         nilai baru, tidak mengubah/menghapus data.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE teachers MODIFY school ENUM('Amaliah 1','Amaliah 2','Amaliah 1 & 2','Staff') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE teachers MODIFY school ENUM('Amaliah 1','Amaliah 2','Amaliah 1 & 2') NOT NULL");
    }
};
