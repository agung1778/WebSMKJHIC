<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insta_posts', function (Blueprint $table) {
            $table->string('instagram_media_id', 191)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('insta_posts', function (Blueprint $table) {
            $table->dropUnique(['instagram_media_id']);
            $table->dropColumn('instagram_media_id');
        });
    }
};