@php
    $item = $teacher ?? null;
    $categoryOptions = [
        'Tenaga Pendidik (Guru)' => [
            'Produktif' => 'Produktif (Guru Kejuruan: TKJ, RPL, DKV, Akuntansi, Animasi, Busana, dll)',
            'Normatif' => 'Normatif (Pendidikan Agama, PPKn, Sejarah, Seni Budaya, Akhlak Tauhid)',
            'Adaptif' => 'Adaptif (Matematika, B. Indonesia, B. Inggris, IPA/IPAS, PJOK, B. Jepang)',
            'Umum' => 'Umum (BK / Bimbingan Konseling, Informatika, KIK, Muatan Lokal)',
            'Struktural' => 'Struktural (Kepala Sekolah, Wakasek, Kepala Program/Kompetensi, Koordinator, Pembina OSIS)',
        ],
        'Tenaga Kependidikan (Staff)' => [
            'Administrasi / TU' => 'Tata Usaha / Administrasi / Persuratan / Kesiswaan',
            'Laboran' => 'Laboran / Asisten Lab Komputer, Bahasa & Sains',
            'Teknisi' => 'Teknisi IT / Maintenance / Multimedia / Web / Operator / Sarpras',
            'Perpustakaan' => 'Perpustakaan / Pustakawan',
            'Keuangan' => 'Keuangan / Bendahara / Kasir',
            'Keamanan' => 'Keamanan / Satpam / Security',
            'Kebersihan' => 'Kebersihan / Pramubakti (OB) / Pelaksana Sarpras',
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
                    <x-admin-components::field name="position" label="Jabatan Utama" :required="true" :value="$item->position ?? null"
                        placeholder="Contoh: Kepala Kompetensi TKJ, Guru Produktif, Pramubakti (OB), Security" icon="fa-solid fa-briefcase" />
                </div>
                <div>
                    <x-admin-components::field name="subject" label="Mata Pelajaran (Jika Guru)" :required="false" :value="$item->subject ?? null"
                        placeholder="Contoh: Pemrograman Web, PAI (Kosongkan jika staf)" icon="fa-solid fa-book-open" />
                </div>
                <div>
                    <x-admin-components::field name="category" label="Kategori Kepegawaian" :required="false" type="select"
                        :options="$categoryOptions" :value="$item->category ?? 'Produktif'" />
                </div>
                <div>
                    <x-admin-components::field name="order_column" label="Urutan / Prioritas Tampil" :required="false" type="number" :value="$item->order_column ?? 999"
                        placeholder="Contoh: 1, 10, 50..." icon="fa-solid fa-arrow-down-1-9"
                        hint="Angka lebih kecil tampil paling atas di website (1 = Paling Atas / Pimpinan)." />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="additional_duties" label="Tugas Tambahan / Job Tambahan (Opsional)" :required="false" :value="$item->additional_duties ?? null"
                        placeholder="Contoh: Wali Kelas XII PPLG 1, Pembina OSIS, Operator, Laboran PPLG" icon="fa-solid fa-list-check" />
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