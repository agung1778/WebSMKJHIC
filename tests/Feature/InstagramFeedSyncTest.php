<?php

namespace Tests\Feature;

use App\Models\InstaPost;
use App\Services\InstagramFeedSyncService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramFeedSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('insta_posts', function (Blueprint $table) {
            $table->id();
            $table->string('caption')->nullable();
            $table->string('path');
            $table->string('post_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $migration = require database_path('migrations/2026_11_13_000001_add_instagram_media_id_to_insta_posts_table.php');
        $migration->up();

        Storage::fake('public');
        Config::set('services.instagram.account_id', '123456789');
        Config::set('services.instagram.access_token', 'test-token');
        Config::set('services.instagram.api_version', 'v23.0');

        Http::fake([
            'graph.facebook.com/v23.0/123456789/media*' => Http::response([
                'data' => [[
                    'id' => 'instagram-media-newest',
                    'caption' => 'Kegiatan terbaru',
                    'media_type' => 'IMAGE',
                    'media_url' => 'https://scontent.cdninstagram.com/photo-newest.jpg',
                    'permalink' => 'https://www.instagram.com/p/newest/',
                    'timestamp' => '2026-11-12T10:00:00+0000',
                ], [
                    'id' => 'instagram-media-older',
                    'caption' => 'Kegiatan sebelumnya',
                    'media_type' => 'IMAGE',
                    'media_url' => 'https://scontent.cdninstagram.com/photo-older.jpg',
                    'permalink' => 'https://www.instagram.com/p/older/',
                    'timestamp' => '2026-11-11T10:00:00+0000',
                ]],
            ]),
            'scontent.cdninstagram.com/*' => Http::response('fake-image', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);
    }

    public function test_it_syncs_instagram_media_without_creating_duplicates(): void
    {
        $sync = app(InstagramFeedSyncService::class);

        $this->assertSame(2, $sync->sync());
        $this->assertSame(2, $sync->sync());

        $newestPost = InstaPost::latest()->firstOrFail();
        $this->assertSame('instagram-media-newest', $newestPost->instagram_media_id);
        $this->assertSame('Kegiatan terbaru', $newestPost->caption);
        $this->assertSame('https://www.instagram.com/p/newest/', $newestPost->post_url);
        $this->assertDatabaseCount('insta_posts', 2);
        Storage::disk('public')->assertExists($newestPost->path);
        Http::assertSentCount(4);
    }
}