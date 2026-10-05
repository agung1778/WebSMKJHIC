@php
    $item = $spmbSetting ?? null;
    $value = fn ($k) => $item?->$k;
@endphp

<div class="space-y-6">
    <div class="form-section space-y-5">
        <h4 class="form-section-title"><i class="fa-solid fa-pen-nib" style="color:var(--brand)"></i> Konten Teks &amp; Status</h4>
        <div class="form-grid-2">
            <div>
                <x-admin-components::field name="status" label="Status Pendaftaran" :required="true" type="select"
                    :options="['Buka' => 'Pendaftaran Buka', 'Tutup' => 'Pendaftaran Tutup']" :value="$value('status')" />
            </div>
            <div>
                <x-admin-components::field name="wave_name" label="Nama Gelombang / Badge Teks"
                    placeholder="Cth: Gelombang Inden Dibuka!" icon="fa-solid fa-flag" :value="$value('wave_name')" />
            </div>
            <div>
                <x-admin-components::field name="wave_category" label="Kategori Gelombang"
                    placeholder="Cth: Gelombang 1, Reguler" icon="fa-solid fa-tag" :value="$value('wave_category')" />
            </div>
            <div>
                <x-admin-components::field name="period_date" label="Periode Pendaftaran"
                    placeholder="Cth: 1 Oktober 2026 - 4 Januari 2027" icon="fa-solid fa-calendar-days"
                    :value="$value('period_date')" />
            </div>
            <div>
                <x-admin-components::field name="quota_note" label="Keterangan Tambahan / Kuota"
                    placeholder="Cth: *Kuota Terbatas" icon="fa-solid fa-users" :value="$value('quota_note')" />
            </div>
            <div>
                <x-admin-components::field name="registration_link" label="Link Pendaftaran (PPDB)"
                    placeholder="Cth: https://ppdb.smkamaliah.sch.id" icon="fa-solid fa-link"
                    :value="$value('registration_link')" />
            </div>
            <div style="grid-column:1/-1">
                <x-admin-components::field name="wave_description" label="Deskripsi Gelombang" type="textarea" :rows="4"
                    placeholder="Jelaskandetail gelombang: kuota, jalur pendaftaran, syarat, dsb."
                    :value="$value('wave_description')" />
            </div>
        </div>
    </div>

    <div class="form-section space-y-5">
        <h4 class="form-section-title"><i class="fa-solid fa-images" style="color:var(--brand)"></i> Media &amp; Brosur</h4>
        <p class="field-hint">*Kosongkan file jika tidak ingin mengubah gambar/file yang sudah ada.</p>
        <div class="form-grid-2">
            <div class="media-box">
                <label class="app-label">Brosur Miring Kiri <span class="optional">(belakang/kiri)</span></label>
                <x-admin-components::dropzone name="brochure_image_1" accept="image/*" :current="$value('brochure_image_1')" />
            </div>
            <div class="media-box">
                <label class="app-label">Brosur Miring Kanan <span class="optional">(depan/kanan)</span></label>
                <x-admin-components::dropzone name="brochure_image_2" accept="image/*" :current="$value('brochure_image_2')" />
            </div>
            <div class="media-box">
                <label class="app-label">Brosur Penuh <span class="optional">(untuk modal pop-up)</span></label>
                <x-admin-components::dropzone name="brochure_full_image" accept="image/*" :current="$value('brochure_full_image')" />
            </div>
            <div class="media-box">
                <label class="app-label">File Brosur Download (PDF)</label>
                <x-admin-components::dropzone name="brochure_file" accept="application/pdf" :current="$value('brochure_file')" />
                @if ($value('brochure_file'))
                    <a class="app-btn app-btn-sm mt-2" href="{{ asset('storage/' . $value('brochure_file')) }}" target="_blank">
                        <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> PDF Tersedia
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="form-section">
        <h4 class="form-section-title"><i class="fa-solid fa-eye" style="color:var(--brand)"></i> Tampilkan</h4>
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
            <span class="toggle-switch">
                <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $value('is_active') ?? false))>
                <span class="track"></span>
            </span>
            <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan data ini di halaman Info SPMB</span>
        </label>
        <p class="field-hint">Hanya satu data yang aktif. Saat diaktifkan, data lain otomatis dinonaktifkan.</p>
    </div>

    <div class="form-section space-y-5">
        <h4 class="form-section-title"><i class="fa-solid fa-window-maximize" style="color:var(--brand)"></i> Tampilan Popup PPDB</h4>
        <p class="field-hint">Popup ini muncul otomatis di halaman website ketika status pendaftaran <b>Buka</b>.</p>

        <div class="form-grid-2">
            <div>
                <x-admin-components::field name="popup_frequency" label="Kapan Popup Muncul" type="select"
                    :options="\App\Models\SpmbSetting::popupFrequencies()"
                    :value="$value('popup_frequency') ?? 'session'"
                    hint="Pilih 'Sekali per sesi browser' agar popup muncul lagi setiap pengunjung me-refresh halaman." />
            </div>
            <div>
                <x-admin-components::field name="popup_delay" label="Jeda Tampil (milidetik)" type="number" min="0" max="10000"
                    :value="$value('popup_delay') ?? 900" icon="fa-solid fa-hourglass-half"
                    hint="Contoh: 900 = muncul 0,9 detik setelah halaman terbuka." />
            </div>
            <div>
                <x-admin-components::field name="popup_theme" label="Warna Banner Popup" type="select"
                    :options="\App\Models\SpmbSetting::popupThemes()" :value="$value('popup_theme') ?? 'hijau'" />
            </div>
            <div>
                <x-admin-components::field name="popup_button_text" label="Teks Tombol Daftar"
                    placeholder="Cth: Daftar Sekarang" icon="fa-solid fa-mouse-pointer-click"
                    :value="$value('popup_button_text')" />
            </div>
            <div style="grid-column:1/-1">
                <x-admin-components::field name="popup_subtitle" label="Judul Kecil (Subtitle)"
                    placeholder="Cth: Penerimaan Peserta Didik Baru" icon="fa-solid fa-heading"
                    :value="$value('popup_subtitle')" />
            </div>
            <div style="grid-column:1/-1">
                <x-admin-components::field name="popup_title" label="Judul Utama Popup"
                    placeholder="Cth: Pendaftaran Murid Baru 2026/2027 Telah Dibuka" icon="fa-solid fa-heading"
                    :value="$value('popup_title')" />
            </div>
            <div style="grid-column:1/-1">
                <x-admin-components::field name="popup_badge" label="Teks Badge (label hijau)"
                    placeholder="Cth: Pendaftaran Buka" icon="fa-solid fa-tag" :value="$value('popup_badge')" />
            </div>
        </div>

        <div class="form-grid-2">
            <div class="media-box">
                <label class="app-label">Logo di Popup <span class="optional">(kosong = logo sekolah)</span></label>
                <x-admin-components::dropzone name="popup_logo" accept="image/*" :current="$value('popup_logo')" />
            </div>
            <div class="media-box">
                <label class="app-label">Gambar Utama Popup <span class="optional">(foto/ilustrasi)</span></label>
                <x-admin-components::dropzone name="popup_image" accept="image/*" :current="$value('popup_image')" />
            </div>
        </div>

        <input type="hidden" name="popup_enabled" value="0">
        <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
            <span class="toggle-switch">
                <input type="checkbox" name="popup_enabled" value="1" @checked((bool) old('popup_enabled', $value('popup_enabled') ?? true))>
                <span class="track"></span>
            </span>
            <span class="text-sm font-medium" style="color:var(--text-2)">Aktifkan popup pengumuman di website</span>
        </label>
        <p class="field-hint">Matikan kalau tidak ingin popup tampil, tanpa mengubah data pendaftaran.</p>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.spmb_settings.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item && $item->exists ? 'Simpan Perubahan' : 'Simpan Data SPMB' }}
    </button>
</div>