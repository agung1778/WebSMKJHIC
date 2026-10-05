<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            // Kontrol popup
            'popup_enabled'    => 'boolean',
            'popup_frequency'  => 'string',
            'popup_theme'      => 'string',
            'popup_delay'      => 'unsignedSmallInteger',

            // Teks popup
            'popup_subtitle'   => 'string',
            'popup_title'      => 'string',
            'popup_badge'      => 'string',
            'popup_button_text' => 'string',

            // Media popup
            'popup_logo'       => 'string',
            'popup_image'      => 'string',
        ];

        $defaults = [
            'popup_enabled'    => true,
            'popup_frequency'  => 'session',
            'popup_theme'      => 'hijau',
            'popup_delay'      => 900,
            'popup_subtitle'   => 'Penerimaan Peserta Didik Baru',
            'popup_title'      => 'Pendaftaran Murid Baru',
            'popup_badge'      => 'Pendaftaran Buka',
            'popup_button_text' => 'Daftar Sekarang',
        ];

        Schema::table('spmb_settings', function (Blueprint $table) use ($columns, $defaults) {
            foreach ($columns as $name => $type) {
                if (Schema::hasColumn('spmb_settings', $name)) {
                    continue;
                }

                if ($name === 'popup_enabled') {
                    $table->boolean($name)->default($defaults[$name])->after('status');
                } elseif ($name === 'popup_delay') {
                    $table->unsignedSmallInteger($name)->default($defaults[$name])->after('popup_frequency');
                } else {
                    $table->{$type}($name)->default($defaults[$name] ?? null)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'popup_enabled', 'popup_frequency', 'popup_theme', 'popup_delay',
            'popup_subtitle', 'popup_title', 'popup_badge', 'popup_button_text',
            'popup_logo', 'popup_image',
        ];

        Schema::table('spmb_settings', function (Blueprint $table) use ($columns) {
            $existing = array_values(array_filter(
                $columns,
                fn ($c) => Schema::hasColumn('spmb_settings', $c)
            ));

            if ($existing) {
                $table->dropColumn($existing);
            }
        });
    }
};