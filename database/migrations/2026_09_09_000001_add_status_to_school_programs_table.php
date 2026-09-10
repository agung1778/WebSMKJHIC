<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan status publikasi untuk Educational Programs (additif, tidak mengubah data lama).
     */
    public function up(): void
    {
        Schema::table('school_programs', function (Blueprint $table) {
            $table->enum('status', ['published', 'draft', 'archived'])
                ->default('published')
                ->after('publisher');
        });
    }

    public function down(): void
    {
        Schema::table('school_programs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};