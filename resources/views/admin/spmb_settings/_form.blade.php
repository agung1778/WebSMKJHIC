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
                    :value="$value('popup_frequency') ?? 'always'"
                    hint="'Setiap halaman & refresh' paling disarankan agar pengunjung selalu melihat pengumuman." />
            </div>
            <div>
                <x-admin-components::field name="popup_delay" label="Jeda Tampil (milidetik)" type="number" min="0" max="10000"
                    :value="$value('popup_delay') ?? 900" icon="fa-solid fa-hourglass-half"
                    hint="Contoh: 900 = muncul 0,9 detik setelah halaman terbuka." />
            </div>
            <div>
                <label class="field-label" for="field-popup_theme">Warna Banner Popup</label>
                <select name="popup_theme" id="field-popup_theme" class="app-select" data-popup-source="popup_theme">
                    @foreach (\App\Models\SpmbSetting::popupThemes() as $optValue => $optLabel)
                        <option value="{{ $optValue }}" @selected((string) old('popup_theme', $value('popup_theme') ?? 'hijau') === (string) $optValue)>{{ $optLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label" for="field-popup_size">Lebar Popup</label>
                <select name="popup_size" id="field-popup_size" class="app-select" data-popup-source="popup_size">
                    @foreach (\App\Models\SpmbSetting::popupSizes() as $optValue => $optLabel)
                        <option value="{{ $optValue }}" @selected((string) old('popup_size', $value('popup_size') ?? 'sedang') === (string) $optValue)>{{ $optLabel }}</option>
                    @endforeach
                </select>
                <div class="field-hint">Popup menyesuaikan layar HP secara otomatis.</div>
            </div>
            <div>
                <label class="field-label" for="field-popup_position">Posisi Popup di Layar</label>
                <select name="popup_position" id="field-popup_position" class="app-select" data-popup-source="popup_position">
                    @foreach (\App\Models\SpmbSetting::popupPositions() as $optValue => $optLabel)
                        <option value="{{ $optValue }}" @selected((string) old('popup_position', $value('popup_position') ?? 'tengah') === (string) $optValue)>{{ $optLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label" for="field-popup_button_text">Teks Tombol Daftar</label>
                <input type="text" name="popup_button_text" id="field-popup_button_text" class="app-input"
                    data-popup-source="popup_button_text" placeholder="Cth: Daftar Sekarang"
                    value="{{ old('popup_button_text', $value('popup_button_text')) }}">
            </div>
            <div style="grid-column:1/-1">
                <label class="field-label" for="field-popup_subtitle">Judul Kecil (Subtitle)</label>
                <input type="text" name="popup_subtitle" id="field-popup_subtitle" class="app-input"
                    data-popup-source="popup_subtitle" placeholder="Cth: Penerimaan Peserta Didik Baru"
                    value="{{ old('popup_subtitle', $value('popup_subtitle')) }}">
            </div>
            <div style="grid-column:1/-1">
                <label class="field-label" for="field-popup_title">Judul Utama Popup</label>
                <input type="text" name="popup_title" id="field-popup_title" class="app-input"
                    data-popup-source="popup_title" placeholder="Cth: Pendaftaran Murid Baru 2026/2027 Telah Dibuka"
                    value="{{ old('popup_title', $value('popup_title')) }}">
            </div>
            <div style="grid-column:1/-1">
                <label class="field-label" for="field-popup_badge">Teks Badge (label hijau)</label>
                <input type="text" name="popup_badge" id="field-popup_badge" class="app-input"
                    data-popup-source="popup_badge" placeholder="Cth: Pendaftaran Buka"
                    value="{{ old('popup_badge', $value('popup_badge')) }}">
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

        <div class="space-y-3">
            <input type="hidden" name="popup_show_image" value="0">
            <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                <span class="toggle-switch">
                    <input type="checkbox" name="popup_show_image" value="1" data-popup-source="popup_show_image"
                        @checked((bool) old('popup_show_image', $value('popup_show_image') ?? true))>
                    <span class="track"></span>
                </span>
                <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan gambar di dalam popup</span>
            </label>

            <input type="hidden" name="popup_show_detail_button" value="0">
            <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                <span class="toggle-switch">
                    <input type="checkbox" name="popup_show_detail_button" value="1" data-popup-source="popup_show_detail_button"
                        @checked((bool) old('popup_show_detail_button', $value('popup_show_detail_button') ?? true))>
                    <span class="track"></span>
                </span>
                <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan tombol "Lihat Detail Info"</span>
            </label>

            <input type="hidden" name="popup_enabled" value="0">
            <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                <span class="toggle-switch">
                    <input type="checkbox" name="popup_enabled" value="1" @checked((bool) old('popup_enabled', $value('popup_enabled') ?? true))>
                    <span class="track"></span>
                </span>
                <span class="text-sm font-medium" style="color:var(--text-2)">Aktifkan popup pengumuman di website</span>
            </label>
        </div>
        <p class="field-hint">Matikan kalau tidak ingin popup tampil, tanpa mengubah data pendaftaran.</p>
    </div>

    <div class="form-section space-y-4">
        <h4 class="form-section-title"><i class="fa-solid fa-eye" style="color:var(--brand)"></i> Pratinjau Popup</h4>
        <p class="field-hint">Tampilan di bawah ikut berubah mengikuti isian form di atas.</p>

        <x-ppdb-popup :setting="$spmbSetting" :preview="true" />

        <script>
            (function () {
                var frame = document.querySelector('[data-popup-frame]');
                if (!frame) return;

                var widths = { kecil: '420px', sedang: '560px', besar: '720px' };
                var aligns = { atas: 'flex-start', tengah: 'center', bawah: 'flex-end' };
                var themes = {
                    hijau: 'linear-gradient(135deg, #63cd00 0%, #3e9b00 55%, #282829 100%)',
                    gelap: 'linear-gradient(135deg, #3f3f46 0%, #27272a 60%, #18181b 100%)',
                    biru: 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #172554 100%)',
                    ungu: 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 55%, #3b0764 100%)',
                    jingga: 'linear-gradient(135deg, #f59e0b 0%, #ea580c 55%, #7c2d12 100%)'
                };

                function fill(key, value) {
                    var targets = frame.querySelectorAll('[data-popup-preview="' + key + '"]');
                    for (var i = 0; i < targets.length; i++) targets[i].textContent = value;
                }

                function toggle(key, visible) {
                    var targets = frame.querySelectorAll('[data-popup-preview="' + key + '"]');
                    for (var i = 0; i < targets.length; i++) {
                        targets[i].style.display = visible ? '' : 'none';
                    }
                }

                function apply() {
                    var fields = document.querySelectorAll('[data-popup-source]');

                    for (var i = 0; i < fields.length; i++) {
                        var el = fields[i];
                        var key = el.getAttribute('data-popup-source');
                        var value = el.type === 'checkbox' ? el.checked : el.value;

                        if (el.type === 'checkbox') {
                            toggle(key, value);
                        } else if (key === 'popup_size') {
                            frame.style.setProperty('--ppdb-width', widths[value] || widths.sedang);
                        } else if (key === 'popup_position') {
                            frame.style.setProperty('--ppdb-align', aligns[value] || aligns.tengah);
                        } else if (key === 'popup_theme') {
                            var banner = frame.querySelector('[data-popup-preview="banner"]');
                            if (banner) banner.style.background = themes[value] || themes.hijau;
                        } else if (value !== '') {
                            fill(key, value);
                        }
                    }
                }

                document.addEventListener('input', apply);
                document.addEventListener('change', apply);
                apply();
            })();
        </script>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.spmb_settings.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item && $item->exists ? 'Simpan Perubahan' : 'Simpan Data SPMB' }}
    </button>
</div>