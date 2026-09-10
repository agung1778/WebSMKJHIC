@php
    $item = $teacher ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Data Guru & Staf</h4>
            <div class="form-grid-2">
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="name" label="Nama Lengkap" :required="true" :value="$item->name ?? null"
                        placeholder="Nama lengkap dengan gelar" icon="fa-solid fa-user" />
                </div>
                <div>
                    <x-admin-components::field name="position" label="Jabatan" :required="true" :value="$item->position ?? null"
                        placeholder="Contoh: Guru Kejuruan, Kepala Sekolah" icon="fa-solid fa-briefcase" />
                </div>
                <div>
                    <x-admin-components::field name="subject" label="Mata Pelajaran" :required="true" :value="$item->subject ?? null"
                        placeholder="Contoh: Pemrograman Web" icon="fa-solid fa-book-open" />
                </div>
                <div>
                    <x-admin-components::field name="school" label="Sekolah" :required="true" type="select"
                        :options="['Amaliah 1' => 'Amaliah 1', 'Amaliah 2' => 'Amaliah 2', 'Amaliah 1 & 2' => 'Amaliah 1 & 2']" :value="$item->school ?? null" />
                </div>
                <div>
                    <x-admin-components::field name="category" label="Kategori" :required="true" type="select"
                        :options="['Produktif' => 'Produktif', 'Normatif' => 'Normatif', 'Adaptif' => 'Adaptif', 'Umum' => 'Umum', 'Struktural' => 'Struktural']" :value="$item->category ?? null" />
                </div>
            </div>
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Foto</h4>
        <x-admin-components::dropzone name="photo" label="Foto Guru / Staf" :required="!$item" :current="$item->photo ?? null"
            hint="Rasio 4:5 atau 1:1. Maks. 5MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.teachers.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Data' }}
    </button>
</div>