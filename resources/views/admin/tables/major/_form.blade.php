@php
    $item = $major ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Jurusan</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="name" label="Nama Jurusan" :required="true" :value="$item->name ?? null"
                        placeholder="Contoh: Pengembangan Perangkat Lunak & Gim" icon="fa-solid fa-graduation-cap" />
                </div>
                <div>
                    <x-admin-components::field name="abbreviation" label="Singkatan" :value="$item->abbreviation ?? null"
                        placeholder="Contoh: PPLG" hint="Singkatan opsional yang tampil di kartu jurusan." />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="competency_head" label="Kepala Kompetensi Keahlian" :required="true"
                        :value="$item->competency_head ?? null" placeholder="Nama lengkap kepala program" icon="fa-solid fa-user-tie" />
                </div>
            </div>
        </div>

        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi & Keunggulan</h4>
            <x-admin-components::field name="description" label="Deskripsi Jurusan" :required="true" type="trix" :value="$item->description ?? null" />
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="tag" label="Tag / Kata Kunci" type="textarea" :rows="4" :value="$item->tag ?? null"
                        placeholder="Satu tag per baris:&#10;Koding&#10;Desain&#10;Kewirausahaan" hint="Digunakan untuk kata kunci pencarian." />
                </div>
                <div>
                    <x-admin-components::field name="advantage" label="Keunggulan Jurusan" type="textarea" :rows="4" :value="$item->advantage ?? null"
                        placeholder="Satu poin per baris:&#10;Kurikulum industri&#10;Guru profesional" />
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="form-section space-y-4">
            <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Media Jurusan</h4>
            <x-admin-components::dropzone name="image" label="Gambar Utama" :required="!$item" :current="$item->image ?? null"
                hint="Rasio 16:9 disarankan. Maks. 5MB." />
            <x-admin-components::dropzone name="logo" label="Logo Jurusan" :current="$item->logo ?? null"
                hint="Gambar logo dengan latar transparan. Maks. 2MB." />
            <x-admin-components::dropzone name="competency_head_photo" label="Foto Kepala Kompetensi" :current="$item->competency_head_photo ?? null"
                hint="Foto potret 4:5. Maks. 2MB." />
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.majors.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Jurusan' }}
    </button>
</div>