@php
    $item = $facility ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Fasilitas</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="name" label="Nama Fasilitas" :required="true" :value="$item->name ?? null"
                        placeholder="Contoh: Laboratorium Komputer" icon="fa-solid fa-building-columns" />
                </div>
                <div>
                    <x-admin-components::field name="type" label="Jenis Fasilitas" :required="true" :value="$item->type ?? null"
                        placeholder="Contoh: Lab, Ruang Kelas, Lapangan" icon="fa-solid fa-shapes" hint="Kelompokkan fasilitas sejenis dengan nama yang sama." />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
            <x-admin-components::field name="description" label="Deskripsi Fasilitas" :required="true" type="trix" :value="$item->description ?? null" />
        </div>
    </div>

    <div class="form-section space-y-4">
        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Gambar Fasilitas</h4>
        <x-admin-components::dropzone name="image" label="Foto Fasilitas" :required="!$item" :current="$item->image ?? null"
            hint="Rasio 4:3 disarankan. Maks. 5MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.facilities.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}
    </button>
</div>