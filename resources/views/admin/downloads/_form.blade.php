@php($item = $download ?? null)
<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="lg:col-span-2 space-y-6">
        <section class="form-section space-y-5">
            <h2 class="form-section-title"><i class="fa-solid fa-pen" aria-hidden="true"></i> Informasi Berkas</h2>
            <div class="field">
                <label for="download-category" class="field-label">Kategori <span class="req">*</span></label>
                <select id="download-category" name="category" class="app-select" x-model="category" required>
                    @foreach(\App\Models\SchoolDownload::categories() as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', $item?->category ?? 'logo') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field" x-show="category === 'pkl'" x-cloak>
                <label for="download-major" class="field-label">Jurusan</label>
                <select id="download-major" name="major_id" class="app-select">
                    <option value="">Umum / Semua Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" @selected((string) old('major_id', $item?->major_id) === (string) $major->id)>{{ $major->name }}</option>
                    @endforeach
                </select>
                @error('major_id')<p class="field-error">{{ $message }}</p>@enderror
                <p class="field-hint">Pilih jurusan untuk contoh laporan khusus. Pilih Umum / Semua Jurusan jika panduannya tidak khusus satu jurusan.</p>
                @if($majors->isEmpty())
                    <p class="field-hint">Belum ada data jurusan. Tambahkan dahulu melalui <a href="{{ route('admin.majors.index') }}" style="color:var(--brand-deep);text-decoration:underline">menu Jurusan</a>, atau simpan sebagai contoh umum.</p>
                @endif
            </div>
            <x-admin-components::field name="name" label="Nama Berkas" type="textarea" :rows="2" :required="true" :value="$item?->name" placeholder="Contoh: Contoh Laporan PKL — Format Sekolah" hint="Maksimal 200 karakter. Nama panjang akan membungkus agar tetap terbaca di HP." />
            <x-admin-components::field name="description" label="Deskripsi" type="textarea" :rows="3" :value="$item?->description" placeholder="Jelaskan isi berkas, versi, atau petunjuk penggunaannya." />
            <x-admin-components::field name="sort_order" label="Urutan Tampil" type="number" :required="true" :min="0" :max="9999" :value="$item?->sort_order ?? 0" hint="Angka lebih kecil ditampilkan lebih dahulu." />
        </section>
        <section class="form-section space-y-5" x-effect="$el.querySelector('input[type=file]').accept = category === 'pkl' ? '.pdf,.docx' : '.png,.jpg,.jpeg,.webp,.pdf'">
            <h2 class="form-section-title"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> File Asli</h2>
            <p class="field-hint">Logo: PNG, JPG, WebP, atau PDF. Contoh laporan PKL: PDF atau DOCX tanpa macro. Maksimal 10 MB. File tidak dikompresi, diubah ukurannya, atau dikonversi.</p>
            <p class="field-hint" x-show="category === 'pkl'" x-cloak>Gunakan contoh laporan yang sudah diizinkan untuk dibagikan dan tidak memuat data pribadi siswa atau perusahaan.</p>
            @if($item)
                <div style="overflow-wrap:anywhere">
                    <strong>{{ $item->original_name }}</strong>
                    <p class="field-hint">{{ strtoupper($item->extension) }} · {{ $item->formatted_size }} @if($item->dimensions) · {{ $item->dimensions }} @endif</p>
                    @if($fileAvailable ?? false)
                        @if($item->is_image)
                            <div style="padding:16px;border-radius:12px;background:{{ $item->dark_preview ? '#202b25' : '#edf3e9' }}">
                                <img src="{{ route('admin.downloads.preview', $item) }}" alt="{{ $item->name }}" style="max-width:100%;max-height:220px;width:auto;height:auto;object-fit:contain;margin:auto">
                            </div>
                        @endif
                        <a href="{{ route('admin.downloads.download', $item) }}" class="app-btn mt-3"><i class="fa-solid fa-download"></i> Unduh File Saat Ini</a>
                    @else
                        <p class="field-error">File sebelumnya tidak ditemukan. Unggah ulang agar berkas dapat diunduh.</p>
                    @endif
                </div>
            @endif
            <x-admin-components::dropzone name="file" :label="$item ? 'Ganti File' : 'Pilih File'" accept=".png,.jpg,.jpeg,.webp,.pdf,.docx" :required="!$item"
                :hint="$item ? 'Kosongkan jika tidak mengganti file. File lama dihapus setelah file pengganti berhasil disimpan.' : 'Pengunjung akan mendapatkan file asli yang Anda unggah.'" />
        </section>
    </div>
    <section class="form-section space-y-5">
        <h2 class="form-section-title"><i class="fa-solid fa-eye" aria-hidden="true"></i> Publikasi &amp; Pratinjau</h2>
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-start gap-3" for="logo-is-active">
            <input type="checkbox" id="logo-is-active" name="is_active" value="1" @checked((bool) old('is_active', $item?->is_active ?? true))>
            <span>Tampilkan di halaman Unduhan</span>
        </label>
        @error('is_active')<p class="field-error">{{ $message }}</p>@enderror
        <p class="field-hint">Berkas yang disembunyikan tidak dapat dilihat atau diunduh oleh pengunjung. Admin tetap dapat mengunduhnya.</p>
        <div x-show="category === 'logo'">
        <input type="hidden" name="dark_preview" value="0">
        <label class="flex items-start gap-3" for="logo-dark-preview">
            <input type="checkbox" id="logo-dark-preview" name="dark_preview" value="1" @checked((bool) old('dark_preview', $item?->dark_preview ?? false))>
            <span>Gunakan latar pratinjau gelap</span>
        </label>
        @error('dark_preview')<p class="field-error">{{ $message }}</p>@enderror
        <p class="field-hint">Gunakan untuk logo putih atau transparan. Ini hanya mengubah latar tampilan, bukan isi file asli.</p>
        </div>
        <p class="field-hint">PDF dan DOCX ditampilkan dengan ikon dokumen dan diunduh sebagai lampiran, bukan dijalankan di browser.</p>
    </section>
</div>
<div class="form-actions mt-6">
    <a href="{{ route('admin.downloads.index') }}" class="app-btn">Batal</a>
    <button type="submit" class="app-btn app-btn-primary" :disabled="submitting"><i class="fa-solid fa-floppy-disk"></i><span x-text="submitting ? 'Menyimpan…' : 'Simpan Berkas'">Simpan Berkas</span></button>
</div>
