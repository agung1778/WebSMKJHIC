@php
    $item = $writing ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Tulisan</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="title" label="Judul Tulisan" :required="true" :value="$item->title ?? null"
                        placeholder="Judul tulisan / konten halaman" icon="fa-solid fa-heading" />
                </div>
                <div>
                    <x-admin-components::field name="release_date" label="Tanggal Terbit" :required="true" type="date"
                        :value="$item && $item->release_date ? $item->release_date->format('Y-m-d') : null" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Isi Tulisan</h4>
            <x-admin-components::field name="content" label="Konten" :required="true" type="trix" :value="$item->content ?? null"
                hint="Editor mendukung heading, list, tabel, dan kutipan." />
        </div>
    </div>

    <div class="form-section h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-circle-check" style="color:var(--brand)"></i> Informasi</h4>
        <ul class="text-[13px] leading-relaxed space-y-2" style="color:var(--text-2)">
            <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Penerbit diisi otomatis atas nama Anda.</li>
            <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Tulisan tampil pada halaman home &amp; tulisan.</li>
            <li><i class="fa-solid fa-check text-[var(--green)] mr-2"></i>Tanggal terbit mengontrol urutan tampil.</li>
        </ul>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.writings.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Terbitkan Tulisan' }}
    </button>
</div>