<?php

namespace Tests\Feature;

use App\Models\TiktokPost;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TiktokPostCrudTest extends TestCase
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
            '2026_11_23_064609_create_tiktok_posts_table.php',
        ] as $file) {
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations/' . $file,
            ]);
        }

        Storage::fake('public');
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

    public function test_guest_cannot_access_admin_tiktok_posts(): void
    {
        $response = $this->get(route('admin.tiktok-posts.index'));
        $response->assertRedirect('/developer');
    }

    public function test_admin_can_view_tiktok_posts_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.tiktok-posts.index'))
            ->assertStatus(200)
            ->assertSee('Feed TikTok')
            ->assertSee('Tambah Video TikTok');
    }

    public function test_admin_can_create_tiktok_post(): void
    {
        $file = UploadedFile::fake()->image('tiktok_cover.jpg', 600, 600);

        $response = $this->actingAs($this->admin())
            ->post(route('admin.tiktok-posts.store'), [
                'image_file' => $file,
                'caption'    => 'Video TikTok Seru SMK Amaliah',
                'video_url'  => 'https://www.tiktok.com/@smk.amaliah/video/7701197482784541973',
                'is_active'  => '1',
            ]);

        $response->assertRedirect(route('admin.tiktok-posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tiktok_posts', [
            'caption'  => 'Video TikTok Seru SMK Amaliah',
            'video_id' => '7701197482784541973',
            'is_active' => 1,
        ]);

        $post = TiktokPost::first();
        Storage::disk('public')->assertExists($post->path);
    }

    public function test_admin_can_update_tiktok_post(): void
    {
        $post = TiktokPost::create([
            'caption'   => 'Caption Lama',
            'path'      => 'uploads/tiktok/old.jpg',
            'video_url' => 'https://www.tiktok.com/@smk/video/111',
            'video_id'  => '111',
            'is_active' => true,
        ]);
        Storage::disk('public')->put('uploads/tiktok/old.jpg', 'fake-image');

        $newFile = UploadedFile::fake()->image('new_cover.jpg');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.tiktok-posts.update', $post), [
                'image_file' => $newFile,
                'caption'    => 'Caption Baru',
                'video_url'  => 'https://www.tiktok.com/@smk/video/222',
                'video_id'   => '222',
                'is_active'  => '1',
            ]);

        $response->assertRedirect(route('admin.tiktok-posts.index'));
        $this->assertDatabaseHas('tiktok_posts', [
            'id'       => $post->id,
            'caption'  => 'Caption Baru',
            'video_id' => '222',
        ]);

        $post->refresh();
        Storage::disk('public')->assertExists($post->path);
        Storage::disk('public')->assertMissing('uploads/tiktok/old.jpg');
    }

    public function test_admin_can_toggle_tiktok_post_status(): void
    {
        $post = TiktokPost::create([
            'caption'   => 'Post Toggle',
            'path'      => 'uploads/tiktok/toggle.jpg',
            'video_url' => 'https://www.tiktok.com/@smk/video/333',
            'video_id'  => '333',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.tiktok-posts.toggle', $post))
            ->assertRedirect(route('admin.tiktok-posts.index'));

        $this->assertFalse((bool) $post->fresh()->is_active);

        $this->actingAs($this->admin())
            ->post(route('admin.tiktok-posts.toggle', $post))
            ->assertRedirect(route('admin.tiktok-posts.index'));

        $this->assertTrue((bool) $post->fresh()->is_active);
    }

    public function test_admin_can_delete_tiktok_post(): void
    {
        $post = TiktokPost::create([
            'caption'   => 'Post To Delete',
            'path'      => 'uploads/tiktok/delete.jpg',
            'video_url' => 'https://www.tiktok.com/@smk/video/444',
            'video_id'  => '444',
            'is_active' => true,
        ]);
        Storage::disk('public')->put('uploads/tiktok/delete.jpg', 'fake-image');

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.tiktok-posts.destroy', $post));

        $response->assertRedirect(route('admin.tiktok-posts.index'));
        $this->assertDatabaseMissing('tiktok_posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing('uploads/tiktok/delete.jpg');
    }
}
