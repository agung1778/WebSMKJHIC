<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pkk_projects', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('major_id'); // Nama Merek
            $table->string('logo')->nullable()->after('photo'); // Logo Brand
            $table->string('contact_info')->nullable()->after('price'); // No WA/Telp
            $table->string('social_media')->nullable()->after('contact_info'); // Link Sosmed
        });
    }

    public function down(): void
    {
        Schema::table('pkk_projects', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'logo', 'contact_info', 'social_media']);
        });
    }
};
