<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\InstaPost;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminThemeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('t', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge();

        // Isolated fixtures: never migrate or write to the application's database.
        foreach ([
            '0001_01_01_000000_create_users_table.php',
            '2025_09_10_070424_create_facility_table.php',
            '2025_09_10_070433_create_major_table.php',
            '2025_09_10_070443_create_news_table.php',
            '2025_09_10_070456_create_partners_table.php',
            '2025_09_10_071051_create_image_table.php',
            '2025_09_11_055544_create_schoolprogram_table.php',
            '2025_09_11_070034_create_teachers_table.php',
            '2025_09_22_161128_create_achievement_table.php',
            '2025_09_22_162426_create_extracurricular_table.php',
            '2025_10_10_112610_create_writing_table.php',
            '2026_02_27_143752_add_role_to_users_table.php',
            '2026_02_28_062855_create_spmb_settings_table.php',
            '2026_08_19_023040_create_traffic_statistics_table.php',
            '2026_09_07_000001_create_insta_posts_table.php',
            '2026_09_07_000002_create_school_settings_table.php',
            '2026_09_09_000001_add_status_to_school_programs_table.php',
            '2026_10_05_000001_add_crud_fields_to_spmb_settings_table.php',
            '2026_10_05_000002_add_popup_fields_to_spmb_settings_table.php',
            '2026_10_05_000003_add_popup_layout_fields_to_spmb_settings_table.php',
        ] as $file) {
            Artisan::call('migrate', ['--force' => true, '--path' => 'database/migrations/'.$file]);
        }
    }

    public function test_superadmin_dashboard_keeps_existing_features_and_navigation(): void
    {
        $response = $this->actingAs(User::factory()->create(['role' => 'superadmin']))
            ->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('admin/admin-theme.css')
            ->assertSee('Content Management System')
            ->assertSee('Website Traffic')
            ->assertSee('Akses Cepat')
            ->assertSee('visitorChart')
            ->assertSee('7 Hari')
            ->assertSee('30 Hari')
            ->assertSee('12 Bulan')
            ->assertSee('data-open-palette', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('data-sidebar-mobile-close', false)
            ->assertSee(route('logout'));

        foreach ([
            'writings', 'image', 'navigations', 'insta-posts', 'news', 'majors',
            'programs', 'facilities', 'partners', 'testimonials', 'pkk',
            'teachers', 'leaders', 'achievements', 'extracurriculars',
            'spmb_settings', 'traffic',
        ] as $resource) {
            $response->assertSee(route('admin.'.$resource.'.index'));
        }
        $response->assertSee(route('admin.school_settings.edit'))
            ->assertSee(route('admin.users'));

        $this->savePreview('dashboard', $response->getContent());
    }

    public function test_admin_does_not_gain_superadmin_permissions(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Manajemen Admin')
            ->assertDontSee(route('admin.users'));

        $this->get(route('admin.users'))->assertForbidden();
    }

    public function test_guest_still_cannot_access_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_content_pages_share_theme_and_keep_forms_and_actions(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'superadmin']));

        $news = $this->get(route('admin.news.index'));
        $news->assertOk()->assertSee('admin/admin-theme.css')
            ->assertSee('Belum ada berita')
            ->assertSee('data-filter-input', false)
            ->assertSee(route('admin.news.create'));
        $this->savePreview('news', $news->getContent());

        $form = $this->get(route('admin.news.create'));
        $form->assertOk()->assertSee('admin/admin-theme.css')
            ->assertSee(route('admin.news.store'))
            ->assertSee('name="_token"', false);
        $this->savePreview('news-create', $form->getContent());
    }

    public function test_media_rows_keep_labels_and_actions_in_responsive_cards(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'superadmin']));
        $image = Image::create([
            'title' => str_repeat('Judul panjang ', 10),
            'filename' => 'sample.jpg',
            'path' => 'images/sample.jpg',
        ]);
        $post = InstaPost::create([
            'caption' => str_repeat('Caption panjang ', 10),
            'path' => 'instagram/sample.jpg',
            'post_url' => 'https://example.com/'.str_repeat('panjang', 20),
            'is_active' => true,
        ]);

        $this->get(route('admin.image.index'))->assertOk()
            ->assertSee('admin-media-layout')
            ->assertSee('data-label="Judul / Path"', false)
            ->assertSee('data-label="Ukuran"', false)
            ->assertSee(route('admin.image.edit', $image))
            ->assertDontSee('min-width:260px', false);

        $this->get(route('admin.insta-posts.index'))->assertOk()
            ->assertSee('admin-media-layout')
            ->assertSee('data-label="Caption"', false)
            ->assertSee('data-label="Status"', false)
            ->assertSee(route('admin.insta-posts.update', $post))
            ->assertDontSee('min-width:220px', false);
    }

    public function test_admin_management_keeps_labeled_fields_and_role_control(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'superadmin']));
        $this->get(route('admin.users'))->assertOk()
            ->assertSee('data-label="Admin"', false)
            ->assertSee('data-label="Aktivitas Terakhir"', false)
            ->assertSee('data-label="Aksi"', false)
            ->assertSee('name="role"', false);
    }

    private function savePreview(string $name, string $html): void
    {
        // Optional local browser fixtures, outside the repository and public routes.
        $directory = getenv('ADMIN_THEME_PREVIEW_DIR');
        if ($directory && is_dir($directory)) {
            file_put_contents($directory.DIRECTORY_SEPARATOR.$name.'.html', $html);
        }
    }
}
