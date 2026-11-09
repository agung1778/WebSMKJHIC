<?php

namespace Tests\Feature;

use App\Models\SchoolDownload as LogoDownload;
use App\Models\User;
use App\Services\DownloadService as LogoDownloadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\Concerns\InteractsWithLogoDownloads;
use Tests\TestCase;

class LogoDownloadCrudTest extends TestCase
{
    use InteractsWithLogoDownloads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpLogoDownloads();
    }

    public function test_admin_can_create_edit_and_replace_a_logo_without_quality_loss(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $file = UploadedFile::fake()->image('logo-besar.png', 2400, 2400);
        $original = file_get_contents($file->getRealPath());
        $this->post(route('admin.logo-downloads.store'), $this->logoAttributes(['file' => $file]))
            ->assertRedirect(route('admin.logo-downloads.index'))->assertSessionHasNoErrors();
        $logo = LogoDownload::firstOrFail();
        $this->assertSame(2400, $logo->width);
        $this->assertSame($admin->id, $logo->uploaded_by);
        $this->assertSame($original, Storage::disk('local')->get($logo->file_path));
        Storage::disk('public')->assertMissing($logo->file_path);

        $this->get(route('admin.logo-downloads.index'))->assertOk()->assertSee('logo-besar.png');
        $this->get(route('admin.logo-downloads.edit', $logo))->assertOk()->assertSee('Unduh File Saat Ini');
        $oldPath = $logo->file_path;
        $this->put(route('admin.logo-downloads.update', $logo), $this->logoAttributes(['name' => 'Versi Baru', 'is_active' => false]))
            ->assertRedirect(route('admin.logo-downloads.index'))->assertSessionHasNoErrors();
        $this->assertSame($oldPath, $logo->fresh()->file_path);
        $this->assertFalse($logo->fresh()->is_active);

        $replacement = UploadedFile::fake()->image('pengganti.jpg', 3000, 2000);
        $replacementBytes = file_get_contents($replacement->getRealPath());
        $this->put(route('admin.logo-downloads.update', $logo), $this->logoAttributes(['file' => $replacement]))
            ->assertRedirect(route('admin.logo-downloads.index'))->assertSessionHasNoErrors();
        $logo->refresh();
        $this->assertSame(3000, $logo->width);
        $this->assertSame(2000, $logo->height);
        $this->assertSame('image/jpeg', $logo->mime_type);
        Storage::disk('local')->assertMissing($oldPath);
        $this->assertSame($replacementBytes, Storage::disk('local')->get($logo->file_path));
    }

    public function test_superadmin_can_upload_and_admin_can_download_hidden_originals(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'superadmin']));
        $this->get(route('admin.logo-downloads.create'))->assertOk()->assertSee('Pilih File');
        $logo = $this->uploadedLogo(['is_active' => false]);
        $this->get(route('admin.logo-downloads.preview', $logo))->assertOk();
        $this->get(route('admin.logo-downloads.download', $logo))->assertOk()->assertDownload('logo-asli.png');
    }

    public function test_delete_removes_record_and_original_file(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $logo = $this->uploadedLogo();
        $this->delete(route('admin.logo-downloads.destroy', $logo))->assertRedirect(route('admin.logo-downloads.index'));
        $this->assertDatabaseMissing('logo_downloads', ['id' => $logo->id]);
        Storage::disk('local')->assertMissing($logo->file_path);
        $this->get(route('public.downloads.logo', $logo))->assertNotFound();
    }

    public function test_webp_and_pdf_uploads_are_kept_in_their_original_formats(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach ([
            UploadedFile::fake()->image('logo.webp', 1500, 1500),
            UploadedFile::fake()->createWithContent('logo.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF"),
        ] as $file) {
            $bytes = file_get_contents($file->getRealPath());
            $this->post(route('admin.logo-downloads.store'), $this->logoAttributes(['file' => $file]))
                ->assertRedirect(route('admin.logo-downloads.index'))->assertSessionHasNoErrors();
            $logo = LogoDownload::latest('id')->firstOrFail();
            $this->assertSame($bytes, Storage::disk('local')->get($logo->file_path));
            $this->assertSame($file->getMimeType(), $logo->mime_type);
            if ($logo->extension === 'pdf') {
                $this->get(route('admin.logo-downloads.preview', $logo))->assertNotFound();
            }
        }
    }

    public function test_client_cannot_override_file_metadata_or_uploader(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post(route('admin.logo-downloads.store'), $this->logoAttributes([
            'file' => UploadedFile::fake()->image('logo.png', 900, 900),
            'file_path' => '../../.env', 'mime_type' => 'text/html', 'uploaded_by' => 99999,
            'file_size' => 0, 'width' => 1, 'height' => 1,
        ]))->assertRedirect(route('admin.logo-downloads.index'))->assertSessionHasNoErrors();
        $logo = LogoDownload::firstOrFail();
        $this->assertStringStartsWith('downloads/', $logo->file_path);
        $this->assertSame('image/png', $logo->mime_type);
        $this->assertSame(900, $logo->width);
        $this->assertSame($admin->id, $logo->uploaded_by);
    }

    public function test_guest_cannot_manage_or_read_private_files(): void
    {
        $logo = $this->uploadedLogo(['is_active' => false]);
        foreach (['index', 'create', 'edit', 'preview', 'download'] as $action) {
            $this->get(route('admin.logo-downloads.'.$action, in_array($action, ['index', 'create']) ? [] : [$logo]))
                ->assertRedirect(route('login'));
        }
        $this->post(route('admin.logo-downloads.store'), [])->assertRedirect(route('login'));
        $this->put(route('admin.logo-downloads.update', $logo), [])->assertRedirect(route('login'));
        $this->delete(route('admin.logo-downloads.destroy', $logo))->assertRedirect(route('login'));
        $this->assertDatabaseHas('logo_downloads', ['id' => $logo->id]);
    }

    public function test_other_roles_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'viewer']));
        $this->get(route('admin.logo-downloads.index'))->assertForbidden();
        $this->post(route('admin.logo-downloads.store'), [])->assertForbidden();
    }

    public function test_upload_validation_blocks_invalid_large_and_script_files(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->post(route('admin.logo-downloads.store'), [])->assertSessionHasErrors(['name', 'file']);
        foreach ([
            UploadedFile::fake()->createWithContent('script.php', '<?php echo "unsafe";'),
            UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
            UploadedFile::fake()->image('too-large.png')->size(10241),
            UploadedFile::fake()->create('corrupt.png', 2, 'image/png'),
        ] as $file) {
            $this->post(route('admin.logo-downloads.store'), $this->logoAttributes(['file' => $file]))->assertSessionHasErrors('file');
        }
        $this->assertSame(0, LogoDownload::count());
        $this->assertSame([], Storage::disk('local')->allFiles('downloads'));
    }

    public function test_failed_replacement_validation_keeps_the_previous_file(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $logo = $this->uploadedLogo();
        $path = $logo->file_path;
        $this->put(route('admin.logo-downloads.update', $logo), $this->logoAttributes([
            'file' => UploadedFile::fake()->createWithContent('invalid.txt', 'not a logo'),
        ]))->assertSessionHasErrors('file');
        $this->assertSame($path, $logo->fresh()->file_path);
        Storage::disk('local')->assertExists($path);
    }

    public function test_failed_database_insert_cleans_up_new_upload(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        LogoDownload::creating(fn () => throw new RuntimeException('Simulated database failure'));
        try {
            app(LogoDownloadService::class)->create($this->logoAttributes(), UploadedFile::fake()->image('new.png'), $admin->id);
            $this->fail('Database failure should propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated database failure', $exception->getMessage());
        } finally {
            LogoDownload::flushEventListeners();
        }
        $this->assertSame(0, LogoDownload::count());
        $this->assertSame([], Storage::disk('local')->allFiles('downloads'));
    }

    public function test_failed_database_update_keeps_old_file_and_removes_new_upload(): void
    {
        $logo = $this->uploadedLogo();
        $oldPath = $logo->file_path;
        LogoDownload::saving(fn () => throw new RuntimeException('Simulated update failure'));
        try {
            app(LogoDownloadService::class)->update($logo, $this->logoAttributes(), UploadedFile::fake()->image('replacement.png'));
            $this->fail('Database failure should propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated update failure', $exception->getMessage());
        } finally {
            LogoDownload::flushEventListeners();
        }
        $this->assertSame($oldPath, $logo->fresh()->file_path);
        Storage::disk('local')->assertExists($oldPath);
        $this->assertCount(1, Storage::disk('local')->allFiles('downloads'));
    }

    public function test_failed_database_delete_keeps_the_original_file(): void
    {
        $logo = $this->uploadedLogo();
        LogoDownload::deleting(fn () => throw new RuntimeException('Simulated delete failure'));
        try {
            app(LogoDownloadService::class)->delete($logo);
            $this->fail('Database failure should propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated delete failure', $exception->getMessage());
        } finally {
            LogoDownload::flushEventListeners();
        }
        $this->assertDatabaseHas('logo_downloads', ['id' => $logo->id]);
        Storage::disk('local')->assertExists($logo->file_path);
    }
}
