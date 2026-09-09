<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolSetting;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        SchoolSetting::firstOrCreate(
            [],
            [
                'jumlah_siswa' => 1160,
                'tahun_ajaran' => '2025/2026',
            ]
        );
    }
}