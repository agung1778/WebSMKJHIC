<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('majors', 'abbreviation')) {
            Schema::table('majors', function (Blueprint $table) {
                $table->string('abbreviation', 30)->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('majors', 'abbreviation')) {
            Schema::table('majors', function (Blueprint $table) {
                $table->dropColumn('abbreviation');
            });
        }
    }
};