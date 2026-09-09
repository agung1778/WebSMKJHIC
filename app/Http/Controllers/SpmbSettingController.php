<?php

namespace App\Http\Controllers;

use App\Models\SpmbSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpmbSettingController extends Controller
{
    // Menampilkan halaman form edit
    public function edit()
    {
        // Ambil data pertama. Jika kosong (seeder belum jalan), buat default baru.
        $setting = SpmbSetting::first();
        if (!$setting) {
            $setting = SpmbSetting::create(['status' => 'Buka']);
        }

        return view('admin.spmb_settings.edit', compact('setting'));
    }

    // Memproses update data
    public function update(Request $request)
    {
        $setting = SpmbSetting::first();

        $validated = $request->validate([
            'status'              => 'required|in:Buka,Tutup',
            'wave_name'           => 'nullable|string|max:255',
            'period_date'         => 'nullable|string|max:255',
            'quota_note'          => 'nullable|string|max:255',
            'registration_link'   => 'nullable|url|max:255',
            'brochure_image_1'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brochure_image_2'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brochure_full_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
            'brochure_file'       => 'nullable|mimes:pdf|max:10240', // Max 10MB untuk PDF
        ]);

        // Daftar file yang perlu diproses
        $fileFields = ['brochure_image_1', 'brochure_image_2', 'brochure_full_image', 'brochure_file'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($setting->$field) {
                    Storage::disk('public')->delete($setting->$field);
                }
                $validated[$field] = $request->file($field)->store('spmb', 'public');
            }
        }

        $setting->update($validated);

        return redirect()->back()->with('success', 'Pengaturan Info SPMB berhasil diperbarui!');
    }
}
