@php
    $item = $testimonial ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Data Alumni</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="name" label="Nama Alumni" :required="true" :value="$item->name ?? null"
                        placeholder="Nama lengkap alumni" icon="fa-solid fa-user" />
                </div>
                <div>
                    <x-admin-components::field name="alumni_year" label="Tahun Lulus" :required="true" :value="$item->alumni_year ?? null"
                        placeholder="Contoh: 2022" icon="fa-solid fa-calendar" />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="major_id" label="Jurusan / Kompetensi" :required="true" type="select"
                        :options="$majors->pluck('name', 'id')->all()" :value="$item->major_id ?? null" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-comment-dots" style="color:var(--brand)"></i> Isi Testimoni</h4>
            <x-admin-components::field name="description" label="Cerita / Kesan" :required="true" type="trix" :value="$item->description ?? null"
                hint="Tulis kesan alumni selama belajar di sekolah." />
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-id-card" style="color:var(--brand)"></i> Foto Alumni</h4>
        <x-admin-components::dropzone name="photo" label="Foto Profil" :required="!$item" :current="$item->photo ?? null"
            hint="Rasio 1:1 (persegi). Maks. 2MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.testimonials.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
    </button>
</div>