@extends('layouts.admin-app')

@section('title', 'Jumlah Peserta Didik')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-users"
            kicker="Website"
            title="Jumlah Peserta Didik"
            subtitle="Atur jumlah peserta didik yang tampil di halaman depan (beranda)." />

        @if (session('success'))
            <div class="p-4 rounded-xl text-sm font-medium border bg-emerald-50 text-emerald-800 border-emerald-200 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-3 sm:gap-4 stat-grid-2">
            <x-admin-components::stat-card label="Total Siswa Saat Ini" :value="number_format($setting->jumlah_siswa ?? 0, 0, ',', '.')" icon="fa-solid fa-users" tone="brand" />
            <x-admin-components::stat-card label="Tahun Ajaran Aktif" :value="$setting->tahun_ajaran ?: 'Belum diisi'" icon="fa-solid fa-calendar" tone="blue" />
        </div>

        <form action="{{ route('admin.school_settings.update') }}" method="POST" class="app-card p-0">
            @csrf
            @method('PUT')

            <div class="p-4 sm:p-6 md:p-8 space-y-5" style="max-width:720px">
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

            <div class="form-actions border-t flex flex-wrap gap-2 p-4 sm:p-6" style="border-color:var(--border)">
                <a class="app-btn app-btn-lg flex-1 sm:flex-initial justify-center" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">Kembali ke </span>Dashboard</a>
                <button type="submit" class="app-btn app-btn-primary app-btn-lg flex-1 sm:flex-initial justify-center"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan</button>
            </div>
        </form>
    </div>
@endsection