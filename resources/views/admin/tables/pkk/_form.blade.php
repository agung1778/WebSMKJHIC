@php
    $item = $pkk ?? null;
    $value = fn ($k) => $item->$k ?? old($k);
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Proyek</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="title" label="Nama Produk / Proyek" :required="true" :value="$value('title')"
                        placeholder="Contoh: Abon Ikan Nila" icon="fa-solid fa-box-open" />
                </div>
                <div>
                    <x-admin-components::field name="major_id" label="Jurusan" :required="true" type="select"
                        :options="$majors->pluck('name', 'id')->all()" :value="$value('major_id')" />
                </div>
                <div>
                    <x-admin-components::field name="category" label="Kategori" :required="true" type="select"
                        :options="['Makanan' => 'Makanan', 'Kerajinan' => 'Kerajinan', 'Jasa' => 'Jasa', 'Teknologi' => 'Teknologi']" :value="$value('category')" />
                </div>
                <div>
                    <x-admin-components::field name="price" label="Harga (opsional)" :value="$value('price')"
                        type="number" step="0.01" min="0" placeholder="0" icon="fa-solid fa-tags" hint="Kosongkan jika tidak dijual / gratisan." />
                </div>
                <div>
                    <x-admin-components::field name="student_class" label="Kelas" :required="true" :value="$value('student_class')"
                        placeholder="Contoh: XI PPLG 1" icon="fa-solid fa-user-graduate" />
                </div>
                <div>
                    <x-admin-components::field name="student_names" label="Nama Siswa / Kelompok" :required="true" :value="$value('student_names')"
                        placeholder="Nama siswa atau kelompok" icon="fa-solid fa-users" />
                </div>
            </div>
        </div>

        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-bullhorn" style="color:var(--brand)"></i> Branding (opsional)</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="brand_name" label="Nama Merek" :value="$value('brand_name')"
                        placeholder="Contoh: Amaliah Culinary" icon="fa-solid fa-trademark" />
                </div>
                <div>
                    <x-admin-components::field name="contact_info" label="Kontak (WA/Telp)" :value="$value('contact_info')"
                        placeholder="Contoh: 0812-3456-7890" icon="fa-brands fa-whatsapp" />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="social_media" label="Link Sosmed" :value="$value('social_media')"
                        placeholder="https://instagram.com/..." icon="fa-solid fa-share-nodes" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
            <x-admin-components::field name="description" label="Cerita Produk / Proyek" :required="true" type="trix" :value="$value('description')"
                hint="Jelaskan proses pembuatan, bahan baku, keunggulan, dan nilai jual produk." />
        </div>
    </div>

    <div class="space-y-6">
        <div class="form-section space-y-4">
            <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Foto Produk</h4>
            <x-admin-components::dropzone name="photo" label="Foto Utama" :required="!$item" :current="$item->photo ?? null"
                hint="JPG/PNG/WebP. Maks. 2MB." />
            <x-admin-components::dropzone name="logo" label="Logo Brand" :current="$item->logo ?? null"
                hint="Logo produk/brand. Maks. 1MB." />
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.pkk.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Proyek' }}
    </button>
</div>