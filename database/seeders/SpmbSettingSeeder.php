<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpmbSetting;

class SpmbSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel benar-benar kosong sebelum diisi (agar tidak ganda)
        if (SpmbSetting::count() == 0) {
            SpmbSetting::create([
                'status' => 'Buka',
                'wave_name' => 'Gelombang Inden Dibuka!',
                'period_date' => '1 Oktober 2025 - 4 Januari 2026',
                'quota_note' => '*Kuota Terbatas',

                // Nanti ini akan disesuaikan path-nya setelah upload dari admin
                'brochure_image_1' => null,
                'brochure_image_2' => null,
                'brochure_full_image' => null,

                'brochure_file' => null,
                'registration_link' => 'https://spmb.smkamaliah.sch.id/login',
            ]);
        }
    }
}
