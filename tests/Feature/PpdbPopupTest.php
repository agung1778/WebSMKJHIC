<?php

namespace Tests\Feature;

use App\Models\SpmbSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Popup pengumuman PPDB di halaman publik.
 *
 * Database sqlite in-memory supaya tidak menyentuh database produksi.
 */
class PpdbPopupTest extends TestCase
{
    private ?string $originalDb = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalDb = config('database.default');

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge();

        // Hanya migrasi yang dibutuhkan tes ini (ada migrasi lain yang khusus MySQL).
        foreach ([
            '2026_02_28_062855_create_spmb_settings_table.php',
            '2026_10_05_000001_add_crud_fields_to_spmb_settings_table.php',
            '2026_10_05_000002_add_popup_fields_to_spmb_settings_table.php',
            '2026_10_05_000003_add_popup_layout_fields_to_spmb_settings_table.php',
        ] as $file) {
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations/' . $file,
            ]);
        }
    }

    protected function tearDown(): void
    {
        config(['database.default' => $this->originalDb]);
        DB::purge();

        parent::tearDown();
    }

    private function buka(array $extra = []): SpmbSetting
    {
        return SpmbSetting::create(array_merge([
            'status' => 'Buka',
            'is_active' => true,
            'wave_name' => 'Gelombang Inden Dibuka!',
            'period_date' => '1 Oktober 2026 - 4 Januari 2027',
            'quota_note' => '*Kuota Terbatas',
            'registration_link' => 'https://ppdb.smkamaliah.sch.id/login',
        ], $extra));
    }

    public function test_popup_muncul_saat_status_pendaftaran_buka(): void
    {
        $this->buka();

        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertSee('Pendaftaran Murid Baru', false)
            ->assertSee('Gelombang Inden Dibuka!')
            ->assertSee('https://ppdb.smkamaliah.sch.id/login', false);
    }

    public function test_popup_tidak_muncul_saat_status_pendaftaran_tutup(): void
    {
        $this->buka(['status' => 'Tutup']);

        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertDontSee('Pendaftaran Murid Baru', false);
    }

    public function test_popup_tidak_muncul_bila_tidak_ada_data_aktif(): void
    {
        SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Nonaktif', 'is_active' => false]);
        $this->buka(['wave_name' => 'Yang Dipakai']);

        // Hanya data aktif yang boleh tampil
        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertSee('Yang Dipakai')
            ->assertDontSee('Nonaktif');
    }

    public function test_popup_menghormati_waktu_tutup_dan_daftar_langsung(): void
    {
        $this->buka([
            'wave_category' => 'Gelombang 1',
            'wave_description' => 'Pendaftaran melalui jalur reguler.',
            'quota_note' => null,
        ]);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString('ppdb-popup-layer', $html);
        $this->assertStringContainsString('Gelombang 1', $html);
        $this->assertStringContainsString('Pendaftaran melalui jalur reguler.', $html);
        // Tidak ada catatan kuota pada data ini
        $this->assertStringNotContainsString('Kuota Terbatas', $html);
    }

    public function test_kunci_penanda_mengikuti_id_dan_waktu_perubahan_data(): void
    {
        $setting = $this->buka();

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString(
            'ppdb-popup-' . $setting->id . '-' . $setting->updated_at->timestamp,
            $html
        );
    }

    public function test_halaman_detail_infospmb_dapat_diakses(): void
    {
        $this->buka();

        $this->get(route('public.spmb.index'))
            ->assertOk()
            ->assertSee('Informasi Pendaftaran');
    }

    public function test_halaman_detail_infospmb_menampilkan_gelombang(): void
    {
        $this->buka(['wave_category' => 'Reguler', 'wave_description' => 'Deskripsi gelombang']);

        $this->get(route('public.spmb.index'))
            ->assertOk()
            ->assertSee('Reguler')
            ->assertSee('Deskripsi gelombang');
    }

    public function test_popup_tidak_muncul_bila_dimatikan_di_admin(): void
    {
        $this->buka(['popup_enabled' => false]);

        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertDontSee('ppdb-popup-layer', false);
    }

    public function test_tampilan_popup_bisa_dikustomisasi(): void
    {
        $this->buka([
            'popup_title' => 'Gelombang II Sudah Dibuka',
            'popup_subtitle' => 'Penerimaan Murid Baru',
            'popup_badge' => 'Segera Daftar',
            'popup_button_text' => 'Daftar Gelombang II',
            'popup_theme' => 'ungu',
            'popup_logo' => 'spmb/logo-kampus.png',
        ]);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString('Gelombang II Sudah Dibuka', $html);
        $this->assertStringContainsString('Segera Daftar', $html);
        $this->assertStringContainsString('Daftar Gelombang II', $html);
        $this->assertStringContainsString('spmb/logo-kampus.png', $html);
        // Tema ungu memakai gradasi khusus
        $this->assertStringContainsString('#8b5cf6', $html);
    }

    public function test_frekuensi_default_popup_selalu_muncul_setiap_refresh(): void
    {
        $setting = $this->buka();

        // Tanpa 설정 frekuensi, popup harus memakai mode 'always' (tidak ada penanda yang disimpan)
        $this->assertSame('always', $setting->popup_frequency ?: 'always');

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString("var mode = 'always'", $html);
        $this->assertStringNotContainsString("mode === 'always'", $html);
    }

    public function test_frekuensi_session_menyimpan_penanda_di_session_storage(): void
    {
        $this->buka(['popup_frequency' => 'session']);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString("var mode = 'session'", $html);
        // sessionStorage bertahan saat refresh, jadi mode ini tidak cocok untuk "muncul tiap refresh"
        $this->assertStringContainsString('window.sessionStorage.getItem(key)', $html);
        $this->assertStringContainsString('window.sessionStorage.setItem(key', $html);
    }

    public function test_frekuensi_always_tidak_menyimpan_penanda(): void
    {
        $this->buka(['popup_frequency' => 'always']);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString("var mode = 'always'", $html);
        // Tidak ada cabang penyimpanan untuk mode ini => selalu muncul, termasuk saat refresh
        $this->assertStringNotContainsString("mode === 'always'", $html);
    }

    public function test_frekuensi_daily_menyimpan_tanggal(): void
    {
        $this->buka(['popup_frequency' => 'daily']);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString("var mode = 'daily'", $html);
        $this->assertStringContainsString('new Date().toDateString()', $html);
    }

    public function test_frekuensi_once_disimpan_permanen(): void
    {
        $this->buka(['popup_frequency' => 'once']);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString("var mode = 'once'", $html);
        $this->assertStringContainsString('window.localStorage.setItem(key, \'1\')', $html);
    }

    public function test_popup_memiliki_jaring_pengaman_jika_alpine_gagal(): void
    {
        $this->buka();

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString('id="ppdb-popup-fallback"', $html);
        $this->assertStringContainsString("if (window.Alpine)", $html);
        $this->assertStringContainsString("removeAttribute('x-cloak')", $html);
    }

    public function test_ukuran_dan_posisi_popup_bisa_diatur(): void
    {
        $this->buka(['popup_size' => 'besar', 'popup_position' => 'bawah']);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString('--ppdb-width: 720px', $html);
        $this->assertStringContainsString('--ppdb-align: flex-end', $html);
    }

    public function test_gambar_popup_bisa_disembunyikan(): void
    {
        $setting = $this->buka(['popup_image' => 'spmb/poster.png', 'popup_show_image' => false]);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringNotContainsString('spmb/poster.png', $html);
        $this->assertFalse($setting->popup_show_image);
    }

    public function test_jeda_tampil_bisa_diatur(): void
    {
        $this->buka(['popup_delay' => 2500]);

        $html = $this->get('/kebijakan-privasi')->assertOk()->getContent();

        $this->assertStringContainsString('var delay = 2500', $html);
        $this->assertStringContainsString('}, delay);', $html);
    }
}