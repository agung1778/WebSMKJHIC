@php
    $item = $newsItem ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Berita</h4>
            <div class="form-grid-2">
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="title" label="Judul Berita" :required="true" :value="$item->title ?? null"
                        placeholder="Contoh: Siswa SMK Amaliah Juara 1 Lomba Kompetensi Siswa" icon="fa-solid fa-heading" />
                </div>
                <div>
                    <x-admin-components::field name="date_published" label="Tanggal Terbit" :required="true" type="date"
                        :value="$item && $item->date_published ? \Carbon\Carbon::parse($item->date_published)->format('Y-m-d') : null" />
                </div>
                <div>
                    <label class="field-label">Penerbit <span class="optional">(otomatis)</span></label>
                    <input type="text" class="app-input" value="{{ auth()->user()->name }}" disabled>
                </div>
            </div>
        </div>

        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Isi Berita</h4>
            <x-admin-components::field name="description" label="Konten Berita" :required="true" type="trix"
                :value="$item->description ?? null" />
            <p class="field-hint">Gunakan editor untuk merapikan teks berita. Gambar dapat disisipkan dari URL file yang sudah diunggah di Media.</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="form-section space-y-4">
            <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Gambar Sampul</h4>
            <x-admin-components::dropzone name="image" label="Thumbnail / Sampul"
                :required="!$item" :current="$item->image ?? null"
                hint="Rasio disarankan 16:9 (mis. 1280×720). Maks. 5MB." />
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-check" style="color:var(--brand)"></i> Ringkasan</h4>
            <ul class="text-[13px] leading-relaxed space-y-2" style="color:var(--text-2)">
                <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Berita tampil pada halaman beranda &amp; daftar berita.</li>
                <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Tanggal terbit mengontrol urutan tampil.</li>
                <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Gambar sampul wajib diunggah untuk berita baru.</li>
            </ul>
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.news.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Berita' }}
    </button>
</div>