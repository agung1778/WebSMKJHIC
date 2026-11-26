@php
    $item = $teacher ?? null;
    $categoryOptions = [
        'Tenaga Pendidik (Guru)' => [
            'Produktif' => 'Produktif (Guru Kejuruan: TKJ, RPL, DKV, Akuntansi)',
            'Normatif' => 'Normatif (Pendidikan Agama, PPKn, Sejarah, Seni Budaya)',
            'Adaptif' => 'Adaptif (Matematika, B. Indonesia, B. Inggris, IPA, PJOK)',
            'Umum' => 'Umum (BK / Bimbingan Konseling, Muatan Lokal)',
            'Struktural' => 'Struktural (Kepala Sekolah, Wakasek, Kepala Program)',
        ],
        'Tenaga Kependidikan (Staff)' => [
            'Administrasi / TU' => 'Tata Usaha / Administrasi Sekolah',
            'Laboran' => 'Laboran / Asisten Lab Komputer & Sains',
            'Teknisi' => 'Teknisi IT / Maintenance Jaringan & Sarpras',
            'Perpustakaan' => 'Perpustakaan / Pustakawan',
            'Keuangan' => 'Keuangan / Kasir / Bendahara',
            'Keamanan' => 'Keamanan / Satpam',
            'Kebersihan' => 'Kebersihan / Petugas Pelaksana Sarpras',
            'Staf' => 'Staf Umum Lainnya',
        ],
    ];
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Data Guru & Staf</h4>
            <div class="form-grid-2">
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="name" label="Nama Lengkap & Gelar" :required="true" :value="$item->name ?? null"
                        placeholder="Contoh: Dr. H. Ahmad Fauzi, M.Pd." icon="fa-solid fa-user" />
                </div>
                <div>
                    <x-admin-components::field name="school" label="Unit / Sekolah" :required="true" type="select"
                        :options="['Amaliah 1 & 2' => 'SMK Amaliah 1 & 2 (Gabungan)', 'Amaliah 1' => 'SMK Amaliah 1', 'Amaliah 2' => 'SMK Amaliah 2', 'Staff' => 'Tenaga Kependidikan / Staff']" :value="$item->school ?? 'Amaliah 1 & 2'" />
                </div>
                <div>
                    <x-admin-components::field name="position" label="Jabatan" :required="true" :value="$item->position ?? null"
                        placeholder="Contoh: Guru Kejuruan RPL, Kepala TU, Staf IT" icon="fa-solid fa-briefcase" />
                </div>
                <div>
                    <x-admin-components::field name="subject" label="Mata Pelajaran" :required="false" :value="$item->subject ?? null"
                        placeholder="Contoh: Pemrograman Web (Kosongkan jika staf)" icon="fa-solid fa-book-open" />
                </div>
                <div>
                    <x-admin-components::field name="category" label="Kategori Kepegawaian" :required="false" type="select"
                        :options="$categoryOptions" :value="$item->category ?? 'Produktif'" />
                </div>
            </div>
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Foto</h4>
        <x-admin-components::dropzone name="photo" label="Foto Guru / Staf" :required="false" :current="$item->photo ?? null"
            hint="Format: JPG, PNG, WEBP. Maks 5MB. Jika dikosongkan, avatar inisial akan otomatis digunakan." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.teachers.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Data' }}
    </button>
</div>