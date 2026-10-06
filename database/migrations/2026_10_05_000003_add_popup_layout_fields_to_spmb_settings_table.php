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
            if (! Schema::hasColumn('spmb_settings', 'popup_size')) {
                $table->string('popup_size')->default('sedang')->nullable();
            }

            if (! Schema::hasColumn('spmb_settings', 'popup_position')) {
                $table->string('popup_position')->default('tengah')->nullable();
            }

            if (! Schema::hasColumn('spmb_settings', 'popup_show_image')) {
                $table->boolean('popup_show_image')->default(true);
            }

            if (! Schema::hasColumn('spmb_settings', 'popup_show_detail_button')) {
                $table->boolean('popup_show_detail_button')->default(true);
            }
        });

        // Frekuensi default: popup muncul setiap kali halaman dibuka / di-refresh.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE spmb_settings MODIFY popup_frequency VARCHAR(255) NOT NULL DEFAULT 'always'");
        }

        // Data yang masih memakai 'session' diubah supaya popup langsung terlihat lagi.
        DB::table('spmb_settings')
            ->where('popup_frequency', 'session')
            ->update(['popup_frequency' => 'always']);
    }

    public function down(): void
    {
        $existing = array_values(array_filter(
            ['popup_size', 'popup_position', 'popup_show_image', 'popup_show_detail_button'],
            fn ($c) => Schema::hasColumn('spmb_settings', $c)
        ));

        if ($existing) {
            Schema::table('spmb_settings', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE spmb_settings MODIFY popup_frequency VARCHAR(255) NOT NULL DEFAULT 'session'");
        }
    }
};