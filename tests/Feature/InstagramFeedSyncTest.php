<?php

namespace Tests\Feature;

use App\Models\InstaPost;
use App\Services\InstagramFeedSyncService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
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
        Storage::fake('local');

        Config::set('services.instagram.api_base', 'https://graph.facebook.com');
        Config::set('services.instagram.account_id', '123456789');
        Config::set('services.instagram.access_token', 'ig.test');
        Config::set('services.instagram.api_version', 'v23.0');
        Config::set('services.instagram.token_path', 'instagram-oauth.json');
        Config::set('services.instagram.sync_limit', 9);
        Config::set('services.instagram.profile_url', 'https://www.instagram.com/smkamaliah');

        Http::fake([
            'graph.facebook.com/v23.0/oauth/access_token*' => function ($request) {
                if (str_contains($request->url(), 'fb_exchange_token')) {
                    return Http::response([
                        'access_token' => 'ig.longlived',
                        'expires_in' => 5184000,
                    ]);
                }

                if (str_contains($request->url(), 'authorization_code')) {
                    return Http::response(['access_token' => 'ig.shortlived']);
                }

                return Http::response([
                    'access_token' => 'ig.refreshed',
                    'token_type' => 'bearer',
                    'expires_in' => 5184000,
                ]);
            },
            'graph.facebook.com/v23.0/me/accounts*' => Http::response([
                'data' => [[
                    'id' => 'page-1',
                    'instagram_business_account' => [
                        'id' => '17841400000000000',
                        'username' => 'smkamaliah',
                    ],
                ]],
            ]),
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

        $newestPost = InstaPost::where('instagram_media_id', 'instagram-media-newest')->firstOrFail();
        $this->assertSame('Kegiatan terbaru', $newestPost->caption);
        $this->assertSame('https://www.instagram.com/p/newest/', $newestPost->post_url);
        $this->assertTrue($newestPost->is_active);
        $this->assertDatabaseCount('insta_posts', 2);

        Storage::disk('public')->assertExists($newestPost->path);
        $this->assertStringStartsWith('uploads/instagram/synced/', $newestPost->path);
        Http::assertSentCount(4);
    }

    public function test_it_refreshes_expired_token_and_persists_the_new_one(): void
    {
        Storage::disk('local')->put('instagram-oauth.json', json_encode([
            'access_token' => 'ig.expired',
            'account_id' => '123456789',
            'expires_at' => time() - 60,
        ]));

        Config::set('services.instagram.access_token', '');
        Config::set('services.instagram.account_id', '');

        $this->assertSame(2, app(InstagramFeedSyncService::class)->sync());

        Http::assertSent(fn ($request) => str_contains($request->url(), '/v23.0/oauth/access_token')
            && str_contains($request->url(), 'ig_refresh_token')
            && str_contains($request->url(), 'ig.expired'));

        $stored = json_decode(Storage::disk('local')->get('instagram-oauth.json'), true);
        $this->assertSame('ig.refreshed', $stored['access_token']);
        $this->assertSame('123456789', $stored['account_id']);

        $this->assertDatabaseHas('insta_posts', [
            'instagram_media_id' => 'instagram-media-newest',
            'caption' => 'Kegiatan terbaru',
        ]);
    }

    public function test_it_exchanges_authorization_code_and_stores_token(): void
    {
        Config::set('services.instagram.app_id', 'app-id');
        Config::set('services.instagram.app_secret', 'app-secret');
        Config::set('services.instagram.redirect_uri', 'https://smkamaliah.sch.id/instagram/callback');
        Config::set('services.instagram.account_id', '');
        Storage::disk('local')->delete('instagram-oauth.json');

        $tokens = app(InstagramFeedSyncService::class)->exchangeAuthorizationCode('code-123');

        $this->assertSame('ig.longlived', $tokens['access_token']);
        $this->assertSame('17841400000000000', $tokens['account_id']);
        $this->assertSame('smkamaliah', $tokens['username']);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/v23.0/oauth/access_token')
            && str_contains($request->url(), 'code=code-123'));

        $stored = json_decode(Storage::disk('local')->get('instagram-oauth.json'), true);
        $this->assertSame('ig.longlived', $stored['access_token']);
        $this->assertSame('17841400000000000', $stored['account_id']);
    }

    public function test_command_skips_gracefully_when_api_is_not_configured(): void
    {
        Config::set('services.instagram.access_token', '');
        Storage::disk('local')->delete('instagram-oauth.json');

        $this->assertSame(0, Artisan::call('instagram:sync'));
        $this->assertStringContainsString('belum dikonfigurasi', Artisan::output());
        Http::assertNothingSent();
    }
}
