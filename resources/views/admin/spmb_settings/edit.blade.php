@extends('layouts.admin-app')

@section('title', 'Pengaturan Info SPMB')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-bullhorn"
            kicker="Website"
            title="Pengaturan Info SPMB"
            subtitle="Atur status, teks, kuota, dan gambar brosur untuk halaman depan website." />

        <form action="{{ route('admin.spmb_settings.update') }}" method="POST" enctype="multipart/form-data" class="app-card p-0">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-5">
                    <h4 class="form-section-title"><i class="fa-solid fa-pen-nib" style="color:var(--brand)"></i> Konten Teks &amp; Status</h4>
                    <div class="form-grid-2">
                        <div>
                            <label class="app-label" for="status">Status Pendaftaran <span class="req">*</span></label>
                            <x-admin-components::field name="status" placeholder=" " :value="old('status', $setting->status)" type="select"
                                :options="['Buka' => 'Pendaftaran Buka', 'Tutup' => 'Pendaftaran Tutup']" />
                        </div>
                        <div>
                            <label class="app-label" for="wave_name">Nama Gelombang / Badge Teks</label>
                            <x-admin-components::field name="wave_name" placeholder="Cth: Gelombang Inden Dibuka!" icon="fa-solid fa-flag"
                                :value="old('wave_name', $setting->wave_name)" />
                        </div>
                        <div>
                            <label class="app-label" for="period_date">Periode Pendaftaran</label>
                            <x-admin-components::field name="period_date" placeholder="Cth: 1 Oktober 2025 - 4 Januari 2026"
                                icon="fa-solid fa-calendar-days" :value="old('period_date', $setting->period_date)" />
                        </div>
                        <div>
                            <label class="app-label" for="quota_note">Keterangan Tambahan / Kuota</label>
                            <x-admin-components::field name="quota_note" placeholder="Cth: *Kuota Terbatas" icon="fa-solid fa-users"
                                :value="old('quota_note', $setting->quota_note)" />
                        </div>
                        <div style="grid-column:1/-1">
                            <label class="app-label" for="registration_link">Link Pendaftaran (PPDB)</label>
                            <x-admin-components::field name="registration_link"
                                placeholder="Cth: https://ppdb.smkamaliah.sch.id" icon="fa-solid fa-link"
                                :value="old('registration_link', $setting->registration_link)" />
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <h4 class="form-section-title"><i class="fa-solid fa-images" style="color:var(--brand)"></i> Media &amp; Brosur</h4>
                    <p class="field-hint">*Kosongkan file jika tidak ingin mengubah gambar/file yang sudah ada.</p>
                    <div class="form-grid-2">
                        <div class="media-box">
                            <label class="app-label">Brosur Miring Kiri <span class="optional">(belakang/kiri)</span></label>
                            <x-admin-components::dropzone name="brochure_image_1" accept="image/*" :current="$setting->brochure_image_1" />
                        </div>
                        <div class="media-box">
                            <label class="app-label">Brosur Miring Kanan <span class="optional">(depan/kanan)</span></label>
                            <x-admin-components::dropzone name="brochure_image_2" accept="image/*" :current="$setting->brochure_image_2" />
                        </div>
                        <div class="media-box">
                            <label class="app-label">Brosur Penuh <span class="optional">(untuk modal pop-up)</span></label>
                            <x-admin-components::dropzone name="brochure_full_image" accept="image/*" :current="$setting->brochure_full_image" />
                        </div>
                        <div class="media-box">
                            <label class="app-label">File Brosur Download (PDF)</label>
                            <x-admin-components::dropzone name="brochure_file" accept="application/pdf"
                                :current="$setting->brochure_file" />
                            @if ($setting->brochure_file)
                                <a class="app-btn app-btn-sm mt-2" href="{{ asset('storage/' . $setting->brochure_file) }}" target="_blank">
                                    <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> PDF Tersedia
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions border-t" style="border-color:var(--border)">
                <a class="app-btn app-btn-lg" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
                <button type="submit" class="app-btn app-btn-primary app-btn-lg"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan SPMB</button>
            </div>
        </form>
    </div>
@endsection