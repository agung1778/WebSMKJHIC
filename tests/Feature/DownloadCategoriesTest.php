<?php

namespace Tests\Feature;

use App\Models\SchoolDownload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithLogoDownloads;
use Tests\TestCase;

class DownloadCategoriesTest extends TestCase
{
    use InteractsWithLogoDownloads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpLogoDownloads();
    }

    public function test_admin_uploads_pkl_docx_and_guest_receives_identical_original(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $file = $this->docxUpload();
        $bytes = file_get_contents($file->getRealPath());
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => 'pkl', 'name' => 'Format Laporan PKL', 'file' => $file, 'dark_preview' => true,
        ]))->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $download = SchoolDownload::firstOrFail();
        $this->assertSame('pkl', $download->category);
        $this->assertFalse($download->dark_preview);
        $this->assertSame('docx', $download->extension);
        $this->assertNull($download->width);
        $this->assertSame($bytes, Storage::disk('local')->get($download->file_path));
        $this->get(route('admin.downloads.edit', $download))->assertOk()->assertSee('Contoh Laporan PKL');
        auth()->logout();
        $response = $this->get(route('public.downloads.file', $download));
        $response->assertOk()->assertDownload('contoh-laporan-pkl.docx')
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $this->assertSame($bytes, file_get_contents($response->baseResponse->getFile()->getPathname()));
        $this->get(route('public.downloads.preview', $download))->assertNotFound();
        $this->get('/unduhan?kategori=pkl')->assertOk()->assertSee('Format Laporan PKL')->assertSee('Dokumen DOCX');
    }

    public function test_category_filters_keep_other_files_and_hidden_reports_out(): void
    {
        $logo = $this->uploadedLogo(['name' => 'Identitas Sekolah QA']);
        $report = $this->uploadedLogo(['category' => 'pkl', 'name' => 'Dokumen Panduan QA'], $this->docxUpload());
        $this->uploadedLogo(['category' => 'pkl', 'name' => 'Belum Dipublikasikan QA', 'is_active' => false], $this->docxUpload());
        $this->get('/unduhan')->assertOk()->assertSee($logo->name)->assertSee($report->name)->assertDontSee('Belum Dipublikasikan QA');
        $this->get('/unduhan?kategori=logo')->assertOk()->assertSee($logo->name)->assertDontSee($report->name);
        $this->get('/unduhan?kategori=pkl')->assertOk()->assertSee($report->name)->assertDontSee($logo->name);
        $this->get('/unduhan?kategori[]=pkl')->assertOk();
        $this->get('/unduhan?kategori=tidak-valid')->assertOk();
    }

    public function test_category_and_file_types_are_validated_together(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => 'pkl', 'file' => UploadedFile::fake()->image('report.png'),
        ]))->assertSessionHasErrors('file');
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => 'logo', 'file' => $this->docxUpload(),
        ]))->assertSessionHasErrors('file');
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => ['logo'], 'file' => UploadedFile::fake()->image('logo.png'),
        ]))->assertSessionHasErrors('category');
        $this->assertSame(0, SchoolDownload::count());
    }

    public function test_disguised_macro_oversized_xml_and_invalid_docx_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach ([
            UploadedFile::fake()->createWithContent('fake.docx', 'not a document'),
            UploadedFile::fake()->createWithContent('fake.docx', "%PDF-1.4\n%%EOF"),
            $this->docxUpload(['word/vbaProject.bin' => 'macro']),
            $this->docxUpload(['word/embeddings/oleObject1.bin' => 'embedded object']),
            $this->docxUpload([], '<!DOCTYPE w:document [<!ENTITY a SYSTEM "file:///etc/passwd">]><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">&a;</w:document>'),
            $this->docxUpload([], str_repeat('x', 5242881)),
            $this->docxUpload([], ''),
        ] as $file) {
            $this->post(route('admin.downloads.store'), $this->logoAttributes(['category' => 'pkl', 'file' => $file]))->assertSessionHasErrors('file');
        }
        $this->assertSame(0, SchoolDownload::count());
        $this->assertSame([], Storage::disk('local')->allFiles('downloads'));
    }

    public function test_changing_category_requires_a_compatible_file_and_preserves_original_on_failure(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $download = $this->uploadedLogo();
        $oldPath = $download->file_path;
        $this->put(route('admin.downloads.update', $download), $this->logoAttributes(['category' => 'pkl']))
            ->assertSessionHasErrors('file');
        $this->assertSame('logo', $download->fresh()->category);
        Storage::disk('local')->assertExists($oldPath);
        $this->put(route('admin.downloads.update', $download), $this->logoAttributes(['category' => 'pkl', 'file' => $this->docxUpload()]))
            ->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $this->assertSame('pkl', $download->fresh()->category);
        Storage::disk('local')->assertMissing($oldPath);
    }

    public function test_legacy_logo_paths_and_urls_still_work(): void
    {
        $download = $this->uploadedLogo();
        $oldPath = $download->file_path;
        Storage::disk('local')->move($oldPath, 'logo-downloads/legacy.png');
        $download->update(['file_path' => 'logo-downloads/legacy.png']);
        $this->get(route('public.downloads.file', $download))->assertOk();
        $this->get(route('public.downloads.logo', $download))->assertOk();
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.logo-downloads.edit', $download))->assertOk();
    }

    public function test_hidden_reports_cannot_be_downloaded_using_new_or_legacy_urls(): void
    {
        $download = $this->uploadedLogo(['category' => 'pkl', 'is_active' => false], $this->docxUpload());
        $this->get(route('public.downloads.file', $download))->assertNotFound();
        $this->get(route('public.downloads.logo', $download))->assertNotFound();
        $this->get(route('public.downloads.preview', $download))->assertNotFound();
        $this->post(route('admin.downloads.store'), [])->assertRedirect(route('login'));
    }

    public function test_category_migration_preserves_existing_records_and_files(): void
    {
        $download = $this->uploadedLogo();
        $path = $download->file_path;
        $bytes = Storage::disk('local')->get($path);
        // Isolate the earlier category migration from the subsequently added composite index.
        Schema::table('logo_downloads', fn ($table) => $table->dropIndex(['category', 'major_id', 'is_active']));
        $migration = require database_path('migrations/2026_11_05_000002_add_category_to_logo_downloads_table.php');
        $migration->down();
        $migration->up();
        $download->refresh();
        $this->assertSame('logo', $download->category);
        $this->assertSame($path, $download->file_path);
        $this->assertSame($bytes, Storage::disk('local')->get($path));
    }
}
