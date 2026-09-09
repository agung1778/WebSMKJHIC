<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Membuat kolom `subject` pada tabel `teachers` menjadi nullable.
     *
     * - Kolom yang diubah : `subject` (string)
     * - Alasan             : Staff / tenaga non-pengajar belum tentu mengampu
     *                         mata pelajaran, sehingga field ini boleh dikosongkan.
     * - Dampak ke data lama: Tidak ada data yang diubah/dihapus; hanya
     *                         mengizinkan nilai NULL untuk entri baru.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE teachers MODIFY subject VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teachers MODIFY subject VARCHAR(255) NOT NULL');
    }
};
