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
            'photo' => 'photos/bambang_foto_lama.jpg',
        ]);

        $csvContent = "Nama Lengkap,Unit / Sekolah,Jabatan,Mata Pelajaran,Tugas Tambahan,Kategori\n"
            . "\"Bambang Kusuma, S.Pd.\",\"Amaliah 1\",\"Wakil Kepala Sekolah Bidang Kurikulum\",\"Fisika Terapan\",\"Wali Kelas XII\",\"Struktural\"\n";

        $file = UploadedFile::fake()->createWithContent('teachers.csv', $csvContent);

        $response = $this->post(route('admin.teachers.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        // Harus tetap ada 1 record, jabatan & tugas terupdate, tapi foto lama tetap terjaga 100%
        $this->assertSame(1, Teacher::where('name', 'Bambang Kusuma, S.Pd.')->count());
        $updated = Teacher::where('name', 'Bambang Kusuma, S.Pd.')->first();
        $this->assertSame('Wakil Kepala Sekolah Bidang Kurikulum', $updated->position);
        $this->assertStringContainsString('Wali Kelas XII', $updated->additional_duties);
        $this->assertSame('photos/bambang_foto_lama.jpg', $updated->photo);
    }

    public function test_import_handles_files_with_kop_surat_or_preamble_rows(): void
    {
        $this->authenticateAdmin();

        $csvContent = "YAYASAN PUSAT STUDI PENGEMBANGAN ISLAM AMALIYAH INDONESIA\n"
            . "SMK AMALIAH 1 & 2 CIAWI BOGOR\n"
            . "DAFTAR ABSENSI DAN DATA GURU TAHUN 2026/2027\n"
            . "\n"
            . "No,Nama Lengkap,Unit / Sekolah,Jabatan,Mata Pelajaran,Tugas Tambahan\n"
            . "1,\"Deni Hendriana, S.Pd.\",\"Amaliah 1\",\"Guru Produktif PPLG\",\"Pemrograman Web\",\"Kepala Bengkel PPLG\"\n";

        $file = UploadedFile::fake()->createWithContent('data_guru_kop.csv', $csvContent);

        $response = $this->post(route('admin.teachers.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $this->assertDatabaseHas('teachers', [
            'name' => 'Deni Hendriana, S.Pd.',
            'position' => 'Guru Produktif PPLG',
            'additional_duties' => 'Kepala Bengkel PPLG',
        ]);
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

    public function test_import_handles_side_by_side_tables_and_replaces_old_data(): void
    {
        $this->authenticateAdmin();

        // Data awal di database
        $existing = Teacher::create([
            'name' => 'Zulfikar Hendriawan, S.T.',
            'school' => 'Amaliah 1',
            'position' => 'Guru Biasa Lama',
            'subject' => 'Fisika Lama',
            'additional_duties' => 'Tugas Lama',
            'category' => 'Adaptif',
            'order_column' => 200,
        ]);

        // File CSV berisi 2 tabel berdampingan (Amaliah 1 di kiri, Amaliah 2 di kanan)
        $csvContent = "No,Nama,JABATAN,TUGAS TAMBAHAN,TUGAS TAMBAHAN,No,Nama,JABATAN,TUGAS TAMBAHAN,TUGAS TAMBAHAN\n"
            . "1,\"Zulfikar Hendriawan, S.T.\",\"Wakil Kepala Sekolah Bidang Kurikulum\",\"Guru Produktif PPLG\",\"Wali Kelas XII PPLG 3\",1,\"Siti Nurjannah, SE.I.\",\"Wakil Kepala Sekolah Bidang Kurikulum\",\"Guru Produktif MP\",\"-\"\n";

        $file = UploadedFile::fake()->createWithContent('side_by_side.csv', $csvContent);

        $response = $this->post(route('admin.teachers.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        // Data Zulfikar harus terupdate jabatannya, mapel dari tugas tambahan, dan tugas tambahannya
        $updatedHendri = Teacher::where('name', 'like', '%Zulfikar Hendriawan%')->first();
        $this->assertNotNull($updatedHendri);
        $this->assertSame('Wakil Kepala Sekolah Bidang Kurikulum', $updatedHendri->position);
        $this->assertStringContainsString('Produktif PPLG', $updatedHendri->subject);
        $this->assertStringContainsString('Wali Kelas XII PPLG 3', $updatedHendri->additional_duties);
        $this->assertSame('Amaliah 1', $updatedHendri->school);

        // Data Siti Nurjannah di tabel kanan harus masuk sebagai guru baru di Amaliah 2
        $sitiWadiah = Teacher::where('name', 'like', '%Siti Nurjannah%')->first();
        $this->assertNotNull($sitiWadiah);
        $this->assertSame('Amaliah 2', $sitiWadiah->school);
        $this->assertSame('Wakil Kepala Sekolah Bidang Kurikulum', $sitiWadiah->position);
        $this->assertStringContainsString('Produktif MP', $sitiWadiah->subject);
    }

    public function test_import_gracefully_handles_invalid_or_corrupt_file_without_500(): void
    {
        $this->authenticateAdmin();

        // 1. File kosong / tanpa header
        $invalidFile = UploadedFile::fake()->createWithContent('invalid.csv', "Col1,Col2\nVal1,Val2\n");
        $response = $this->post(route('admin.teachers.import'), [
            'file' => $invalidFile,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('error');

        // 2. File palsu .xls binary random yang tidak bisa di-shell_exec
        $corruptXls = UploadedFile::fake()->createWithContent('corrupt.xls', "\x00\x01\x02\x03\xFF\xFE\xFD");
        $response2 = $this->post(route('admin.teachers.import'), [
            'file' => $corruptXls,
        ]);
        $response2->assertStatus(302);
        $response2->assertSessionHas('error');
    }
}
