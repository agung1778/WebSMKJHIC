<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Existing reports remain available as general examples.
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite requires the foreign key inline when adding a column.
            DB::statement('ALTER TABLE logo_downloads ADD COLUMN major_id INTEGER REFERENCES majors(id) ON DELETE SET NULL');
        } else {
            Schema::table('logo_downloads', function (Blueprint $table) {
                $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            });
        }
        Schema::table('logo_downloads', function (Blueprint $table) {
            $table->index(['category', 'major_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('logo_downloads', function (Blueprint $table) {
            $table->dropIndex(['category', 'major_id', 'is_active']);
        });
        Schema::table('logo_downloads', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'sqlite') {
                $table->dropColumn('major_id');
            } else {
                $table->dropConstrainedForeignId('major_id');
            }
        });
    }
};
