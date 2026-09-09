<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('traffic_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_token', 191)->index();
            $table->string('ip_address', 64)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('device', 64)->nullable();
            $table->string('os', 64)->nullable();
            $table->string('country', 4)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('page_url', 500);
            $table->timestamp('visited_at')->index();
            $table->timestamps();
        });

        Schema::create('traffic_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_token', 191)->nullable()->index();
            $table->enum('click_type', ['link', 'button'])->default('link')->index();
            $table->string('element_name', 500);
            $table->string('element_url', 1000)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('device', 64)->nullable();
            $table->string('os', 64)->nullable();
            $table->string('country', 4)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('clicked_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_clicks');
        Schema::dropIfExists('traffic_visitors');
    }
};