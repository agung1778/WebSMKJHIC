<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The official DOCX MIME exceeds the original 64-character image MIME column.
        // SQLite does not enforce VARCHAR lengths and cannot natively alter this column.
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('logo_downloads', function (Blueprint $table) {
                $table->string('mime_type', 128)->change();
            });
        }
    }

    public function down(): void
    {
        // Do not shrink: existing DOCX metadata would be truncated or block rollback.
    }
};
