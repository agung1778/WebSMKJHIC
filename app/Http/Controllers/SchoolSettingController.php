<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    // Menampilkan halaman form edit
    public function edit()
    {
        // Ambil data pertama. Jika kosong, buat default baru.
        $setting = SchoolSetting::first();
        if (!$setting) {
            $setting = SchoolSetting::create(['jumlah_siswa' => 0]);
        }

        return view('admin.school_settings.edit', compact('setting'));
    }

    // Memproses update data
    public function update(Request $request)
    {
        $setting = SchoolSetting::first();

        $validated = $request->validate([
            'jumlah_siswa' => 'required|integer|min:0',
            'tahun_ajaran' => 'nullable|string|max:255',
        ], [
            'jumlah_siswa.required' => 'Jumlah siswa harus diisi.',
            'jumlah_siswa.integer'  => 'Jumlah siswa harus berupa angka.',
            'jumlah_siswa.min'      => 'Jumlah siswa tidak boleh negatif.',
        ]);

        $setting->update($validated);

        return redirect()->back()->with('success', 'Data statistik sekolah berhasil diperbarui!');
    }
}