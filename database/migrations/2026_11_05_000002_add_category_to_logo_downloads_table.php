<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logo_downloads', function (Blueprint $table) {
            // Existing logo records remain intact and are categorized as logos.
            $table->string('category', 24)->default('logo')->index();
        });
    }

    public function down(): void
    {
        Schema::table('logo_downloads', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }
};
