<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TeacherDutiesAndStaffTest extends TestCase
{
    use DatabaseTransactions;

    protected function authenticateAdmin(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_test@amaliah.sch.id'],
            [
                'name' => 'Admin Tester',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
        $this->actingAs($admin);
        return $admin;
    }

    public function test_can_create_teacher_with_additional_duties(): void
    {
        $this->authenticateAdmin();

        $response = $this->post(route('admin.teachers.store'), [
            'name' => 'Dr. H. Bambang Sudrajat, M.Kom.',
            'school' => 'Amaliah 1',
            'position' => 'Kepala Kompetensi PPLG',
            'subject' => 'Pemrograman Web',
            'additional_duties' => 'Guru Produktif PPLG, Wali Kelas XII PPLG 1',
            'category' => 'Struktural',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $this->assertDatabaseHas('teachers', [
            'name' => 'Dr. H. Bambang Sudrajat, M.Kom.',
            'additional_duties' => 'Guru Produktif PPLG, Wali Kelas XII PPLG 1',
            'category' => 'Struktural',
        ]);
    }

    public function test_can_create_pramubakti_ob_staff(): void
    {
        $this->authenticateAdmin();

        $response = $this->post(route('admin.teachers.store'), [
            'name' => 'Suryana Saputra',
            'school' => 'Staff',
            'position' => 'Pramubakti (OB)',
            'subject' => null,
            'additional_duties' => 'Pelaksana Sarpras Gedung A',
            'category' => 'Kebersihan',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $this->assertDatabaseHas('teachers', [
            'name' => 'Suryana Saputra',
            'school' => 'Staff',
            'position' => 'Pramubakti (OB)',
            'category' => 'Kebersihan',
        ]);
    }

    public function test_public_teachers_page_shows_staff_and_duties(): void
    {
        $teacher = Teacher::create([
            'name' => 'Dra. Hj. Nurjanah Kusuma, M.Pd.',
            'school' => 'Amaliah 2',
            'position' => 'Guru Matematika',
            'subject' => 'Matematika Terapan',
            'additional_duties' => 'Wali Kelas XI AK 1',
            'category' => 'Adaptif',
            'order_column' => 50,
        ]);

        $response = $this->get(route('public.teachers.index'));
        $response->assertOk();
        $response->assertSee('Dra. Hj. Nurjanah Kusuma, M.Pd.');

        $showResponse = $this->get(route('public.teachers.show', $teacher));
        $showResponse->assertOk();
        $showResponse->assertSee('Wali Kelas XI AK 1');
    }

    public function test_teachers_are_ordered_by_hierarchy(): void
    {
        $top = Teacher::create([
            'name' => 'Prof. Dr. Pimpinan Paling Atas',
            'school' => 'Amaliah 1',
            'position' => 'Direktur Utama',
            'category' => 'Struktural',
            'order_column' => 1,
        ]);

        $bottom = Teacher::create([
            'name' => 'Staff Tingkat Bawah',
            'school' => 'Staff',
            'position' => 'Staf Pelaksana',
            'category' => 'Kebersihan',
            'order_column' => 999,
        ]);

        $ordered = Teacher::ordered()->get();
        $topIndex = $ordered->search(fn ($t) => $t->id === $top->id);
        $bottomIndex = $ordered->search(fn ($t) => $t->id === $bottom->id);

        $this->assertNotFalse($topIndex);
        $this->assertNotFalse($bottomIndex);
        $this->assertLessThan($bottomIndex, $topIndex);
    }

    public function test_import_updates_existing_teacher_automatically(): void
    {
        $this->authenticateAdmin();

        $existing = Teacher::create([
            'name' => 'Bambang Kusuma, S.Pd.',
            'school' => 'Amaliah 1',
            'position' => 'Guru Biasa',
            'subject' => 'Fisika',
            'category' => 'Adaptif',
            'order_column' => 200,
        ]);

        $csvContent = "Nama Lengkap,Unit / Sekolah,Jabatan,Mata Pelajaran,Tugas Tambahan,Kategori\n"
            . "\"Bambang Kusuma, S.Pd.\",\"Amaliah 1\",\"Wakil Kepala Sekolah Bidang Kurikulum\",\"Fisika Terapan\",\"Wali Kelas XII\",\"Struktural\"\n";

        $file = UploadedFile::fake()->createWithContent('teachers.csv', $csvContent);

        $response = $this->post(route('admin.teachers.import'), [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        // Harus tetap ada 1 record, tapi datanya terupdate
        $this->assertSame(1, Teacher::where('name', 'Bambang Kusuma, S.Pd.')->count());
        $updated = Teacher::where('name', 'Bambang Kusuma, S.Pd.')->first();
        $this->assertSame('Wakil Kepala Sekolah Bidang Kurikulum', $updated->position);
        $this->assertStringContainsString('Wali Kelas XII', $updated->additional_duties);
    }

    public function test_deduplicate_route_merges_duplicate_names(): void
    {
        $this->authenticateAdmin();

        $t1 = Teacher::create([
            'name' => 'Siti Nur Aisyah, S.Pd.',
            'school' => 'Amaliah 1',
            'position' => 'Guru Biologi',
            'subject' => 'Biologi',
            'additional_duties' => 'Wali Kelas X',
            'category' => 'Adaptif',
            'order_column' => 200,
        ]);

        $t2 = Teacher::create([
            'name' => 'Siti Nur Aisyah, S.Pd',
            'school' => 'Amaliah 2',
            'position' => 'Guru Kimia',
            'subject' => 'Kimia',
            'additional_duties' => 'Pembina PMR',
            'category' => 'Adaptif',
            'order_column' => 205,
        ]);

        $response = $this->post(route('admin.teachers.deduplicate'));
        $response->assertRedirect(route('admin.teachers.index'));

        // Harus disatukan menjadi 1 record
        $matched = Teacher::where('name', 'like', 'Siti Nur Aisyah%')->get();
        $this->assertSame(1, $matched->count());
        $single = $matched->first();
        $this->assertSame('Amaliah 1 & 2', $single->school);
        $this->assertStringContainsString('Wali Kelas X', $single->additional_duties);
        $this->assertStringContainsString('Pembina PMR', $single->additional_duties);
    }
}
