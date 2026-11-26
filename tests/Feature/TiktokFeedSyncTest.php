<?php

namespace Tests\Feature;

use App\Models\TiktokPost;
use App\Services\TiktokFeedSyncService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TiktokFeedSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('tiktok_posts', function (Blueprint $table) {
            $table->id();
            $table->string('caption')->nullable();
            $table->string('path');
            $table->string('video_url')->nullable();
            $table->string('video_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Storage::fake('public');
        Storage::fake('local');

        Config::set('services.tiktok.api_base', 'https://open.tiktokapis.com');
        Config::set('services.tiktok.access_token', 'act.test');
        Config::set('services.tiktok.refresh_token', 'rft.test');
        Config::set('services.tiktok.client_key', 'client-key');
        Config::set('services.tiktok.client_secret', 'client-secret');
        Config::set('services.tiktok.token_path', 'tiktok-oauth.json');
        Config::set('services.tiktok.sync_limit', 9);
        Config::set('services.tiktok.profile_url', 'https://www.tiktok.com/@smk.amaliah');

        Http::fake([
            'open.tiktokapis.com/v2/oauth/token/*' => Http::response([
                'data' => [
                    'access_token' => 'act.refreshed',
                    'refresh_token' => 'rft.new',
                    'expires_in' => 86400,
                    'refresh_expires_in' => 31536000,
                    'open_id' => 'open-id-1',
                ],
            ]),
            'open.tiktokapis.com/v2/video/list/*' => Http::response([
                'data' => ['videos' => [[
                    'id' => '7701197482784541973',
                    'create_time' => 1762893600,
                    'cover_image_url' => 'https://p16-sign-sg.tiktokcdn.com/cover-new.jpg',
                    'video_description' => 'Kegiatan terbaru SMK Amaliah',
                    'share_url' => 'https://www.tiktok.com/@smk.amaliah/video/7701197482784541973',
                ], [
                    'id' => '7700908599970647316',
                    'create_time' => 1762807200,
                    'cover_image_url' => 'https://p16-sign-sg.tiktokcdn.com/cover-old.jpg',
                    'title' => 'Kegiatan sebelumnya',
                    'share_url' => 'https://www.tiktok.com/@smk.amaliah/video/7700908599970647316',
                ]]],
                'error' => ['code' => 'ok', 'message' => 'Ok'],
            ]),
            'p16-sign-sg.tiktokcdn.com/*' => Http::response('fake-image', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);
    }

    public function test_it_syncs_tiktok_videos_without_creating_duplicates(): void
    {
        $sync = app(TiktokFeedSyncService::class);

        $this->assertSame(2, $sync->sync());
        $this->assertSame(2, $sync->sync());

        $newest = TiktokPost::where('video_id', '7701197482784541973')->firstOrFail();

        $this->assertSame('Kegiatan terbaru SMK Amaliah', $newest->caption);
        $this->assertSame('https://www.tiktok.com/@smk.amaliah/video/7701197482784541973', $newest->video_url);
        $this->assertTrue($newest->is_active);
        $this->assertDatabaseCount('tiktok_posts', 2);

        Storage::disk('public')->assertExists($newest->path);
        $this->assertStringStartsWith('uploads/tiktok/synced/', $newest->path);
    }

    public function test_it_refreshes_expired_token_and_persists_the_new_one(): void
    {
        Storage::disk('local')->put('tiktok-oauth.json', json_encode([
            'access_token' => 'act.expired',
            'refresh_token' => 'rft.old',
            'expires_at' => time() - 60,
        ]));

        Config::set('services.tiktok.access_token', '');
        Config::set('services.tiktok.refresh_token', '');

        $this->assertSame(2, app(TiktokFeedSyncService::class)->sync());

        Http::assertSent(fn ($request) => str_contains($request->url(), '/v2/oauth/token/')
            && str_contains((string) $request->body(), 'rft.old'));

        $stored = json_decode(Storage::disk('local')->get('tiktok-oauth.json'), true);
        $this->assertSame('act.refreshed', $stored['access_token']);
        $this->assertSame('rft.new', $stored['refresh_token']);

        $this->assertDatabaseHas('tiktok_posts', [
            'video_id' => '7701197482784541973',
            'caption' => 'Kegiatan terbaru SMK Amaliah',
        ]);
    }

    public function test_it_exchanges_authorization_code_and_stores_token(): void
    {
        Config::set('services.tiktok.redirect_uri', 'https://smkamaliah.sch.id/tiktok/callback');
        Storage::disk('local')->delete('tiktok-oauth.json');

        $tokens = app(TiktokFeedSyncService::class)->exchangeAuthorizationCode('code-123');

        $this->assertSame('act.refreshed', $tokens['access_token']);
        $this->assertSame('open-id-1', $tokens['open_id']);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/v2/oauth/token/')
            && str_contains((string) $request->body(), 'authorization_code')
            && str_contains((string) $request->body(), 'code-123'));

        $stored = json_decode(Storage::disk('local')->get('tiktok-oauth.json'), true);
        $this->assertSame('act.refreshed', $stored['access_token']);
    }

    public function test_command_skips_gracefully_when_api_is_not_configured(): void
    {
        Config::set('services.tiktok.access_token', '');
        Config::set('services.tiktok.refresh_token', '');
        Storage::disk('local')->delete('tiktok-oauth.json');

        $this->assertSame(0, Artisan::call('tiktok:sync'));
        $this->assertStringContainsString('belum dikonfigurasi', Artisan::output());
        Http::assertNothingSent();
    }
}
