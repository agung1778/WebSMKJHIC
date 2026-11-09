<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\SchoolDownload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithLogoDownloads;
use Tests\TestCase;

class DownloadMajorsTest extends TestCase
{
    use InteractsWithLogoDownloads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpLogoDownloads();
    }

    private function major(string $name): Major
    {
        return Major::create([
            'name' => $name, 'description' => 'Data jurusan untuk pengujian.',
            'competency_head' => 'Penguji', 'publisher' => 'Penguji',
        ]);
    }

    public function test_admin_assigns_a_report_to_an_existing_major(): void
    {
        $major = $this->major('Pengembangan Perangkat Lunak dan Gim');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => 'pkl', 'major_id' => $major->id, 'name' => 'Panduan Laporan Khusus', 'file' => $this->docxUpload(),
        ]))->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $download = SchoolDownload::firstOrFail();
        $this->assertSame($major->id, $download->major_id);
        $this->assertSame($major->name, $download->major->name);
        $this->assertSame($download->id, $major->downloads()->firstOrFail()->id);
        $this->get(route('admin.downloads.edit', $download))->assertOk()->assertSee('value="'.$major->id.'" selected', false);
        $this->get('/unduhan?kategori=pkl')->assertOk()->assertSee('Panduan Laporan Khusus')->assertSee('download-major-label');
    }

    public function test_public_filters_are_strict_and_do_not_expose_hidden_reports(): void
    {
        $first = $this->major('Jurusan Alfa');
        $second = $this->major('Jurusan Beta');
        $this->uploadedLogo(['name' => 'Laporan Alfa Terbuka', 'category' => 'pkl', 'major_id' => $first->id], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Laporan Beta Terbuka', 'category' => 'pkl', 'major_id' => $second->id], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Laporan Lintas Jurusan', 'category' => 'pkl'], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Laporan Alfa Disembunyikan', 'category' => 'pkl', 'major_id' => $first->id, 'is_active' => false], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Identitas Logo']);

        $this->get('/unduhan?kategori=pkl&jurusan='.$first->id)->assertOk()
            ->assertSee('Laporan Alfa Terbuka')->assertDontSee('Laporan Beta Terbuka')
            ->assertDontSee('Laporan Lintas Jurusan')->assertDontSee('Laporan Alfa Disembunyikan')->assertDontSee('Identitas Logo');
        $this->get('/unduhan?jurusan='.$second->id)->assertOk()
            ->assertSee('Laporan Beta Terbuka')->assertDontSee('Laporan Alfa Terbuka')->assertDontSee('Identitas Logo');
        $this->get('/unduhan?kategori=pkl&jurusan=umum')->assertOk()
            ->assertSee('Laporan Lintas Jurusan')->assertDontSee('Laporan Alfa Terbuka')->assertDontSee('Laporan Beta Terbuka');
        $this->get('/unduhan?kategori=pkl')->assertOk()
            ->assertSee('Laporan Alfa Terbuka')->assertSee('Laporan Beta Terbuka')->assertSee('Laporan Lintas Jurusan');
        $this->get('/unduhan?jurusan[]=1')->assertOk();
        $this->get('/unduhan?jurusan=1%20OR%201=1')->assertOk();
    }

    public function test_admin_can_filter_major_and_still_manage_hidden_reports(): void
    {
        $major = $this->major('Jurusan Satu');
        $other = $this->major('Jurusan Dua');
        $this->uploadedLogo(['name' => 'Khusus Tersembunyi', 'category' => 'pkl', 'major_id' => $major->id, 'is_active' => false], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Milik Jurusan Lain', 'category' => 'pkl', 'major_id' => $other->id], $this->docxUpload());
        $this->uploadedLogo(['name' => 'Panduan Umum', 'category' => 'pkl'], $this->docxUpload());
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get(route('admin.downloads.index', ['jurusan' => $major->id]))->assertOk()
            ->assertSee('Khusus Tersembunyi')->assertDontSee('Milik Jurusan Lain')->assertDontSee('Panduan Umum');
        $this->get(route('admin.downloads.index', ['jurusan' => 'umum']))->assertOk()
            ->assertSee('Panduan Umum')->assertDontSee('Khusus Tersembunyi');
    }

    public function test_missing_or_malformed_major_is_rejected_before_file_storage(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach ([999999, -1, 'tidak-valid', [1]] as $value) {
            $this->post(route('admin.downloads.store'), $this->logoAttributes([
                'category' => 'pkl', 'major_id' => $value, 'file' => $this->docxUpload(),
            ]))->assertSessionHasErrors('major_id');
        }
        $this->assertSame(0, SchoolDownload::count());
        $this->assertSame([], Storage::disk('local')->allFiles('downloads'));
    }

    public function test_major_can_be_changed_or_cleared_without_replacing_file(): void
    {
        $first = $this->major('Awal');
        $second = $this->major('Tujuan');
        $download = $this->uploadedLogo(['category' => 'pkl', 'major_id' => $first->id], $this->docxUpload());
        $path = $download->file_path;
        $bytes = Storage::disk('local')->get($path);
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->put(route('admin.downloads.update', $download), $this->logoAttributes(['category' => 'pkl', 'major_id' => $second->id]))
            ->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $this->assertSame($second->id, $download->fresh()->major_id);
        $this->put(route('admin.downloads.update', $download), $this->logoAttributes(['category' => 'pkl', 'major_id' => null]))
            ->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $this->assertNull($download->fresh()->major_id);
        $this->assertSame($path, $download->fresh()->file_path);
        $this->assertSame($bytes, Storage::disk('local')->get($path));
    }

    public function test_logo_category_cannot_retain_a_major(): void
    {
        $major = $this->major('Jurusan Logo');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->post(route('admin.downloads.store'), $this->logoAttributes([
            'category' => 'logo', 'major_id' => $major->id, 'file' => UploadedFile::fake()->image('logo.png'),
        ]))->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $this->assertNull(SchoolDownload::firstOrFail()->major_id);

        $download = $this->uploadedLogo(['category' => 'pkl', 'major_id' => $major->id], UploadedFile::fake()->createWithContent('contoh.pdf', "%PDF-1.4\n%%EOF"));
        $path = $download->file_path;
        $this->put(route('admin.downloads.update', $download), $this->logoAttributes(['category' => 'logo', 'major_id' => $major->id]))
            ->assertRedirect(route('admin.downloads.index'))->assertSessionHasNoErrors();
        $this->assertNull($download->fresh()->major_id);
        $this->assertSame($path, $download->fresh()->file_path);
    }

    public function test_major_deletion_preserves_report_and_original_bytes(): void
    {
        $major = $this->major('Akan Dihapus');
        $download = $this->uploadedLogo(['category' => 'pkl', 'major_id' => $major->id], $this->docxUpload());
        $bytes = Storage::disk('local')->get($download->file_path);
        $major->delete();
        $this->assertNull($download->fresh()->major_id);
        $this->assertSame('Umum / Semua Jurusan', $download->fresh()->major_label);
        $this->assertSame($bytes, Storage::disk('local')->get($download->file_path));
        $this->get(route('public.downloads.file', $download))->assertOk();
    }

    public function test_major_options_are_dynamic_and_an_empty_list_still_supports_general_reports(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get(route('admin.downloads.create'))->assertOk()->assertSee('Belum ada data jurusan');
        $major = $this->major('Nama Sebelumnya');
        $major->update(['name' => 'Nama Jurusan Terbaru']);
        $this->get(route('admin.downloads.create'))->assertOk()->assertSee('Nama Jurusan Terbaru')->assertDontSee('Nama Sebelumnya');
    }

    public function test_public_listing_eager_loads_major_names(): void
    {
        $major = $this->major('Jurusan Tanpa N Plus Satu');
        for ($i = 0; $i < 4; $i++) {
            $this->uploadedLogo(['category' => 'pkl', 'major_id' => $major->id], $this->docxUpload());
        }
        DB::enableQueryLog();
        $this->get('/unduhan?kategori=pkl')->assertOk();
        $queries = array_filter(DB::getQueryLog(), fn ($query) => str_contains($query['query'], 'from "majors"'));
        DB::disableQueryLog();
        $this->assertLessThanOrEqual(2, count($queries));
    }

    public function test_major_migration_preserves_existing_general_reports(): void
    {
        $download = $this->uploadedLogo(['category' => 'pkl'], $this->docxUpload());
        $path = $download->file_path;
        $bytes = Storage::disk('local')->get($path);
        $migration = require database_path('migrations/2026_11_06_000001_add_major_to_school_downloads.php');
        $migration->down();
        $migration->up();
        $download->refresh();
        $this->assertNull($download->major_id);
        $this->assertSame('pkl', $download->category);
        $this->assertSame($path, $download->file_path);
        $this->assertSame($bytes, Storage::disk('local')->get($path));
    }
}
