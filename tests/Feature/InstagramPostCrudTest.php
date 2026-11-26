<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\InstaPost;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramPostCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge();

        foreach ([
            '0001_01_01_000000_create_users_table.php',
            '2026_02_27_143752_add_role_to_users_table.php',
            '2026_09_07_000001_create_insta_posts_table.php',
            '2026_11_13_000001_add_instagram_media_id_to_insta_posts_table.php',
        ] as $file) {
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations/'.$file,
            ]);
        }

        Storage::fake('public');

        // CSRF aktif di lingkungan produksi; middleware lain tetap dijalankan.
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    private function admin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }

    public function test_guest_cannot_access_admin_insta_posts(): void
    {
        $this->get(route('admin.insta-posts.index'))
            ->assertRedirect('/developer');
    }

    public function test_admin_can_view_insta_posts_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.insta-posts.index'))
            ->assertStatus(200)
            ->assertSee('Feed Instagram')
            ->assertSee('Tambah Postingan')
            ->assertSee('Link Postingan Instagram');
    }

    public function test_admin_can_create_insta_post_with_uploaded_photo(): void
    {
        $file = UploadedFile::fake()->image('instagram.jpg', 600, 600);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.insta-posts.store'), [
                'image_file' => $file,
                'caption' => 'Foto terbaru SMK Amaliah',
                'post_url' => 'https://www.instagram.com/p/DAGw8pKxy7z/',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.insta-posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('insta_posts', [
            'caption' => 'Foto terbaru SMK Amaliah',
            'post_url' => 'https://www.instagram.com/p/DAGw8pKxy7z/',
            'instagram_media_id' => 'DAGw8pKxy7z',
            'is_active' => 1,
        ]);

        Storage::disk('public')->assertExists(InstaPost::first()->path);
    }

    public function test_admin_can_create_insta_post_from_link_with_auto_cover(): void
    {
        Http::fake([
            'www.instagram.com/p/Cxyz123/*' => Http::response(
                '<meta property="og:image" content="https://scontent-amt2-1.cdninstagram.com/v/t51/photo.jpg">'
                .'<meta property="og:description" content="Kegiatan sekolah • Instagram photos and videos">',
                200,
                ['Content-Type' => 'text/html']
            ),
            'scontent-amt2-1.cdninstagram.com/*' => Http::response('fake-image', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.insta-posts.store'), [
                'post_url' => 'https://www.instagram.com/p/Cxyz123/',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.insta-posts.index'));
        $response->assertSessionHas('success');

        $post = InstaPost::firstOrFail();
        $this->assertSame('Kegiatan sekolah', $post->caption);
        $this->assertStringStartsWith('uploads/instagram/auto-Cxyz123.', $post->path);
        Storage::disk('public')->assertExists($post->path);
    }

    public function test_admin_can_update_insta_post(): void
    {
        $post = InstaPost::create([
            'caption' => 'Caption Lama',
            'path' => 'uploads/instagram/old.jpg',
            'post_url' => 'https://www.instagram.com/p/old/',
            'instagram_media_id' => 'old',
            'is_active' => true,
        ]);
        Storage::disk('public')->put('uploads/instagram/old.jpg', 'fake-image');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.insta-posts.update', $post), [
                'image_file' => UploadedFile::fake()->image('new.jpg'),
                'caption' => 'Caption Baru',
                'post_url' => 'https://www.instagram.com/reel/NewReel1/',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.insta-posts.index'));

        $this->assertDatabaseHas('insta_posts', [
            'id' => $post->id,
            'caption' => 'Caption Baru',
            'instagram_media_id' => 'NewReel1',
        ]);

        $post->refresh();
        Storage::disk('public')->assertExists($post->path);
        Storage::disk('public')->assertMissing('uploads/instagram/old.jpg');
    }

    public function test_admin_can_toggle_insta_post_status(): void
    {
        $post = InstaPost::create([
            'caption' => 'Post Toggle',
            'path' => 'uploads/instagram/toggle.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.insta-posts.toggle', $post))
            ->assertRedirect(route('admin.insta-posts.index'));

        $this->assertFalse((bool) $post->fresh()->is_active);

        $this->actingAs($this->admin())
            ->post(route('admin.insta-posts.toggle', $post))
            ->assertRedirect(route('admin.insta-posts.index'));

        $this->assertTrue((bool) $post->fresh()->is_active);
    }

    public function test_admin_can_delete_insta_post(): void
    {
        $post = InstaPost::create([
            'caption' => 'Post To Delete',
            'path' => 'uploads/instagram/delete.jpg',
            'is_active' => true,
        ]);
        Storage::disk('public')->put('uploads/instagram/delete.jpg', 'fake-image');

        $this->actingAs($this->admin())
            ->delete(route('admin.insta-posts.destroy', $post))
            ->assertRedirect(route('admin.insta-posts.index'));

        $this->assertDatabaseMissing('insta_posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing('uploads/instagram/delete.jpg');
    }
}
