<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolLeader;

class SchoolLeaderSeeder extends Seeder
{
    public function run(): void
    {
        SchoolLeader::firstOrCreate(
            ['school' => 'Amaliah 1', 'name' => 'Tisna Sudrajat S.Kom., Gr., ACA'],
            [
                'position'  => 'Kepala Sekolah SMK Amaliah 1',
                'quote'     => '"Pendidikan adalah paspor masa depan karena hari esok adalah milik mereka yang mempersiapkannya hari ini."',
                'order_column' => 1,
                'is_active' => true,
            ]
        );

        SchoolLeader::firstOrCreate(
            ['school' => 'Amaliah 2', 'name' => 'Dr. Gugun Gunadi, S.Pd.I., M.Pd.'],
            [
                'position'  => 'Kepala Sekolah SMK Amaliah 2',
                'quote'     => '"Sekolah adalah rumah untuk tumbuh — di sini kami membimbing peserta didik tidak hanya menguasai kompetensi kerja, tetapi juga membentuk akhlak dan sikap profesional. Bersama orang tua dan mitra industri, kami membuka peluang nyata agar setiap lulusan membangun masa depan yang bermakna."',
                'order_column' => 2,
                'is_active' => true,
            ]
        );
    }
}