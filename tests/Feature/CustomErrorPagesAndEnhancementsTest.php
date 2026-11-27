<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CustomErrorPagesAndEnhancementsTest extends TestCase
{
    use DatabaseTransactions;

    protected function authenticateAdmin(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@smkamaliah.sch.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ]
        );

        $this->actingAs($admin);

        return $admin;
    }

    public function test_custom_404_page_renders_with_brand_styling(): void
    {
        $response = $this->get('/halaman-yang-pasti-tidak-ada-12345');

        $response->assertStatus(404);
        $response->assertSee('404 - Halaman Tidak Ditemukan');
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee('Mungkin Anda Mencari:');
        $response->assertSee('Program Kejuruan');
    }

    public function test_custom_error_views_exist(): void
    {
        $this->assertTrue(view()->exists('errors.404'));
        $this->assertTrue(view()->exists('errors.403'));
        $this->assertTrue(view()->exists('errors.500'));
        $this->assertTrue(view()->exists('errors.503'));
        $this->assertTrue(view()->exists('errors.419'));
    }

    public function test_admin_can_export_teachers_to_csv(): void
    {
        $this->authenticateAdmin();

        $response = $this->get(route('admin.teachers.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment; filename="data_guru_dan_staf_smk_amaliah_', $response->headers->get('Content-Disposition'));
        
        $content = $response->getContent();
        $this->assertStringContainsString('Nama Lengkap', $content);
        $this->assertStringContainsString('Tugas Tambahan / Job Tambahan', $content);
        $this->assertStringContainsString('Urutan Hierarki', $content);
    }

    public function test_public_pages_include_opengraph_meta_tags(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('property="og:site_name" content="SMK Amaliah 1 & 2 Ciawi"', false);
        $response->assertSee('property="og:type" content="website"', false);
        $response->assertSee('name="twitter:card" content="summary_large_image"', false);
    }

    public function test_floating_widget_contains_targeted_whatsapp_options(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami via WhatsApp');
        $response->assertSee('SPMB SMK Amaliah 1');
        $response->assertSee('SPMB SMK Amaliah 2');
        $response->assertSee('Tata Usaha & Informasi Umum', false);
    }

    public function test_karya_siswa_page_loads(): void
    {
        $response = $this->get('/karya-siswa');
        $response->assertStatus(200);

        $first = \App\Models\PkkProject::first();
        if ($first) {
            $detailResponse = $this->get(route('public.pkk.detail', $first->id));
            $detailResponse->assertStatus(200);
        }
    }
}
