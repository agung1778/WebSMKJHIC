<?php

namespace Tests\Feature;

use App\Models\SpmbSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * CRUD Info SPMB: daftar, tambah, ubah, hapus + penjaga data aktif.
 *
 * Tes ini memakai database sqlite in-memory supaya tidak menyentuh
 * database produksi.
 */
class SpmbSettingCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge();

        // Hanya migrasi yang dibutuhkan tes ini (ada migrasi lain yang khusus MySQL).
        foreach ([
            '0001_01_01_000000_create_users_table.php',
            '2026_02_27_143752_add_role_to_users_table.php',
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

        Storage::fake('public');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'superadmin']);
    }

    public function test_admin_bisa_menambah_data_spmb(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.spmb_settings.store'), [
            'status' => 'Buka',
            'wave_name' => 'Gelombang 1',
            'wave_category' => 'Reguler',
            'wave_description' => 'Kuota 120 siswa',
            'period_date' => '1 Oktober 2026 - 4 Januari 2027',
            'quota_note' => '*Kuota Terbatas',
            'registration_link' => 'https://ppdb.smkamaliah.sch.id',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.spmb_settings.index'));
        $this->assertDatabaseHas('spmb_settings', [
            'wave_name' => 'Gelombang 1',
            'wave_category' => 'Reguler',
            'is_active' => true,
        ]);
    }

    public function test_validasi_wajib_ditegakkan(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.spmb_settings.store'), ['status' => 'Tidak Valid'])
            ->assertSessionHasErrors(['status']);

        $this->assertSame(0, SpmbSetting::count());
    }

    public function test_hanya_satu_data_yang_aktif(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.spmb_settings.store'), [
            'status' => 'Buka',
            'wave_name' => 'Gelombang 1',
            'is_active' => '1',
        ]);

        $second = $this->actingAs($admin)->post(route('admin.spmb_settings.store'), [
            'status' => 'Tutup',
            'wave_name' => 'Gelombang 2',
            'is_active' => '1',
        ]);

        $second->assertRedirect(route('admin.spmb_settings.index'));

        $this->assertSame(2, SpmbSetting::count());
        $this->assertSame(1, SpmbSetting::where('is_active', true)->count());
        $this->assertSame('Gelombang 2', SpmbSetting::active()->wave_name);
    }

    public function test_halaman_daftar_dan_halaman_tambah_dapat_diakses(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.spmb_settings.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.spmb_settings.create'))->assertOk();
    }

    public function test_update_dan_hapus_data(): void
    {
        $admin = $this->admin();

        $setting = SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Lama', 'is_active' => true]);

        $this->actingAs($admin)->put(route('admin.spmb_settings.update', $setting), [
            'status' => 'Tutup',
            'wave_name' => 'Baru',
            'is_active' => '0',
        ])->assertRedirect(route('admin.spmb_settings.index'));

        $setting->refresh();
        $this->assertSame('Baru', $setting->wave_name);
        $this->assertSame('Tutup', $setting->status);
        $this->assertFalse($setting->is_active);

        $this->actingAs($admin)
            ->delete(route('admin.spmb_settings.destroy', $setting))
            ->assertRedirect(route('admin.spmb_settings.index'));

        $this->assertSame(0, SpmbSetting::count());
    }

    public function test_hapus_data_aktif_mengaktifkan_data_lainnya(): void
    {
        $admin = $this->admin();

        SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Aktif', 'is_active' => true]);
        $lain = SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Cadangan']);

        $this->actingAs($admin)->delete(route('admin.spmb_settings.destroy', SpmbSetting::where('wave_name', 'Aktif')->first()));

        $this->assertSame(1, SpmbSetting::count());
        $this->assertSame(1, SpmbSetting::where('is_active', true)->count());
        $lain->refresh();
        $this->assertTrue($lain->is_active);
    }

    public function test_halaman_admin_menampilkan_daftar_data(): void
    {
        $admin = $this->admin();
        SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Gelombang Inden', 'is_active' => true]);

        $this->actingAs($admin)
            ->get(route('admin.spmb_settings.index'))
            ->assertOk()
            ->assertSee('Gelombang Inden');
    }

    public function test_admin_bisa_mengatur_tampilan_popup(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.spmb_settings.store'), [
            'status' => 'Buka',
            'wave_name' => 'Gelombang 3',
            'popup_enabled' => '1',
            'popup_frequency' => 'session',
            'popup_theme' => 'ungu',
            'popup_delay' => 1500,
            'popup_subtitle' => 'Penerimaan Murid Baru',
            'popup_title' => 'Gelombang 3 Telah Dibuka',
            'popup_badge' => 'Ayo Daftar',
            'popup_button_text' => 'Daftar Gelombang 3',
            'popup_logo' => UploadedFile::fake()->image('logo.png', 300, 300),
        ])->assertRedirect(route('admin.spmb_settings.index'));

        $setting = SpmbSetting::firstWhere('wave_name', 'Gelombang 3');

        $this->assertNotNull($setting);
        $this->assertTrue($setting->popup_enabled);
        $this->assertSame('session', $setting->popup_frequency);
        $this->assertSame('ungu', $setting->popup_theme);
        $this->assertSame(1500, $setting->popup_delay);
        $this->assertSame('Ayo Daftar', $setting->popup_badge);
        $this->assertSame('Daftar Gelombang 3', $setting->popup_button_text);
        $this->assertNotNull($setting->popup_logo);
        Storage::disk('public')->assertExists($setting->popup_logo);
    }

    public function test_pratinjau_popup_tampil_di_halaman_admin(): void
    {
        SpmbSetting::create([
            'status' => 'Buka',
            'is_active' => true,
            'popup_title' => 'Pratinjau Gelombang 4',
        ]);

        $html = $this->actingAs($this->admin())
            ->get(route('admin.spmb_settings.edit', SpmbSetting::query()->latest('id')->first()))
            ->assertOk()
            ->getContent();

        // Mode pratinjau: tidak memakai layer fixed, ada panel live preview
        $this->assertStringContainsString('data-popup-frame', $html);
        $this->assertStringNotContainsString('id="ppdb-popup-fallback"', $html);
        $this->assertStringContainsString('Pratinjau Gelombang 4', $html);
        $this->assertStringContainsString('data-popup-source="popup_title"', $html);
    }

    public function test_admin_bisa_mengatur_ukuran_posisi_dan_tampilan_popup(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.spmb_settings.store'), [
            'status' => 'Buka',
            'popup_size' => 'kecil',
            'popup_position' => 'atas',
            'popup_show_image' => '0',
            'popup_show_detail_button' => '1',
        ]);

        $response->assertRedirect(route('admin.spmb_settings.index'));

        $setting = SpmbSetting::query()->latest('id')->first();

        $this->assertSame('kecil', $setting->popup_size);
        $this->assertSame('atas', $setting->popup_position);
        $this->assertFalse($setting->popup_show_image);
        $this->assertTrue($setting->popup_show_detail_button);
        // Frekuensi default agar popup terlihat di setiap halaman/refresh
        $this->assertSame('always', $setting->popup_frequency);
    }

    public function test_ukuran_dan_posisi_popup_hanya_menerima_nilai_valid(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.spmb_settings.store'), [
                'status' => 'Buka',
                'popup_size' => 'raksasa',
                'popup_position' => 'tengah-kiri',
            ])
            ->assertSessionHasErrors(['popup_size', 'popup_position']);
    }

    public function test_popup_bisa_dimatikan_lewat_admin(): void
    {
        $setting = SpmbSetting::create(['status' => 'Buka', 'wave_name' => 'Lama', 'is_active' => true, 'popup_enabled' => true]);

        $this->actingAs($this->admin())->put(route('admin.spmb_settings.update', $setting), [
            'status' => 'Buka',
            'popup_enabled' => '0',
        ])->assertRedirect(route('admin.spmb_settings.index'));

        $this->assertFalse($setting->refresh()->popup_enabled);
    }

    public function test_frekuensi_popup_hanya_menerima_nilai_yang_valid(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.spmb_settings.store'), [
                'status' => 'Buka',
                'popup_frequency' => 'nonsense',
            ])
            ->assertSessionHasErrors('popup_frequency');
    }
}