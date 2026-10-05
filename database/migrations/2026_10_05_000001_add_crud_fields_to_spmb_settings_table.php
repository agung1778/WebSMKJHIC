<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('spmb_settings', 'wave_category')) {
                $table->string('wave_category')->nullable()->after('wave_name');
            }

            if (!Schema::hasColumn('spmb_settings', 'wave_description')) {
                $table->text('wave_description')->nullable()->after('wave_category');
            }

            if (!Schema::hasColumn('spmb_settings', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('status');
                $table->index('is_active');
            }
        });

        // Jadikan data lama (paling lama) sebagai data aktif agar halaman publik tidak kosong.
        $firstId = DB::table('spmb_settings')->orderBy('id')->value('id');
        if ($firstId) {
            DB::table('spmb_settings')->where('id', $firstId)->update(['is_active' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('spmb_settings', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['wave_category', 'wave_description', 'is_active'],
                fn ($c) => Schema::hasColumn('spmb_settings', $c)
            ));

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};