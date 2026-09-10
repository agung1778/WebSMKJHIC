@extends('layouts.admin-app')

@section('title', 'Jumlah Peserta Didik')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-users"
            kicker="Website"
            title="Jumlah Peserta Didik"
            subtitle="Atur jumlah peserta didik yang tampil di halaman depan (beranda)." />

        <form action="{{ route('admin.school_settings.update') }}" method="POST" class="app-card p-0">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-5" style="max-width:720px">
                <h4 class="form-section-title"><i class="fa-solid fa-users" style="color:var(--brand)"></i> Peserta Didik</h4>
                <div class="form-grid-2">
                    <div>
                        <label class="app-label" for="jumlah_siswa">Jumlah Peserta Didik <span class="req">*</span></label>
                        <x-admin-components::field name="jumlah_siswa" type="number" min="0" placeholder="Cth: 1160"
                            icon="fa-solid fa-user-group" :value="old('jumlah_siswa', $setting->jumlah_siswa)" />
                    </div>
                    <div>
                        <label class="app-label" for="tahun_ajaran">Tahun Ajaran <span class="optional">(opsional)</span></label>
                        <x-admin-components::field name="tahun_ajaran" placeholder="Cth: 2025/2026"
                            icon="fa-solid fa-calendar" :value="old('tahun_ajaran', $setting->tahun_ajaran)" />
                    </div>
                </div>
                <p class="field-hint">Angka ini diperbarui manual oleh admin tiap tahun ajaran dan langsung tampil di homepage.</p>
            </div>

            <div class="form-actions border-t" style="border-color:var(--border)">
                <a class="app-btn app-btn-lg" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
                <button type="submit" class="app-btn app-btn-primary app-btn-lg"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan</button>
            </div>
        </form>
    </div>
@endsection