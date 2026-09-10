@php
    $item = $extracurricular ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Ekskul</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="name" label="Nama Ekstrakurikuler" :required="true" :value="$item->name ?? null"
                        placeholder="Contoh: Futsal, Paskibra, Robotik" icon="fa-solid fa-futbol" />
                </div>
                <div>
                    <x-admin-components::field name="type" label="Tipe" :required="true" type="select"
                        :options="['Wajib' => 'Wajib', 'Pilihan' => 'Pilihan']" :value="$item->type ?? null" />
                </div>
                <div>
                    <x-admin-components::field name="coach" label="Pembina" :required="true" :value="$item->coach ?? null"
                        placeholder="Nama pembina ekskul" icon="fa-solid fa-whistle" />
                </div>
                <div>
                    <x-admin-components::field name="contact" label="Kontak / Instagram" :required="true" :value="$item->contact ?? null"
                        placeholder="Contoh: @smk_amaliah_futsal" icon="fa-brands fa-instagram" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
            <x-admin-components::field name="description" label="Deskripsi Kegiatan" :required="true" type="trix" :value="$item->description ?? null"
                hint="Sebutkan jadwal latihan, kegiatan rutin, dan pencapaian ekskul." />
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Foto</h4>
        <x-admin-components::dropzone name="image" label="Gambar Ekskul" :required="!$item" :current="$item->image ?? null"
            hint="Rasio 4:3 disarankan. Maks. 5MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.extracurriculars.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Ekskul' }}
    </button>
</div>