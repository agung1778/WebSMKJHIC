@php
    $item = $leader ?? null;
    $imgCurrent = $item && $item->image
        ? (str_starts_with($item->image, 'uploads/') || str_starts_with($item->image, 'http') ? $item->image : null)
        : null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Data Pemimpin Sekolah</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="school" label="Sekolah" :required="true" type="select"
                        :options="['Amaliah 1' => 'Amaliah 1', 'Amaliah 2' => 'Amaliah 2', 'Amaliah 1 & 2' => 'Amaliah 1 & 2']" :value="$item->school ?? null" />
                </div>
                <div>
                    <x-admin-components::field name="name" label="Nama Lengkap" :required="true" :value="$item->name ?? null"
                        placeholder="Nama lengkap dengan gelar" icon="fa-solid fa-user" />
                </div>
                <div>
                    <x-admin-components::field name="position" label="Jabatan" :required="true" :value="$item->position ?? null"
                        placeholder="Contoh: Kepala Sekolah SMK Amaliah 1" icon="fa-solid fa-briefcase" />
                </div>
                <div>
                    <x-admin-components::field name="order_column" label="Urutan" type="number" :value="$item->order_column ?? 0"
                        placeholder="0" icon="fa-solid fa-sort" />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="quote" label="Kutipan / Sambutan" type="textarea" :rows="4"
                        :value="$item->quote ?? null" placeholder="Tulis kutipan atau sambutan pemimpin sekolah…" />
                </div>
            </div>
        </div>

        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-share-nodes" style="color:var(--brand)"></i> Media Sosial</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="facebook_url" label="Facebook" :value="$item->facebook_url ?? null"
                        placeholder="https://facebook.com/…" icon="fa-brands fa-facebook-f" />
                </div>
                <div>
                    <x-admin-components::field name="instagram_url" label="Instagram" :value="$item->instagram_url ?? null"
                        placeholder="https://instagram.com/…" icon="fa-brands fa-instagram" />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="linkedin_url" label="LinkedIn" :value="$item->linkedin_url ?? null"
                        placeholder="https://linkedin.com/in/…" icon="fa-brands fa-linkedin-in" />
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4 h-fit">
        <div class="form-section space-y-4">
            <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Foto</h4>
            <x-admin-components::dropzone name="image" label="Foto Pemimpin" :required="!$item" :current="$imgCurrent"
                hint="Rasio 1:1 (bulat). Maks. 5MB." />
            @if ($item && !$imgCurrent && $item->image)
                <p class="text-sm" style="color:var(--text-3)">Gambar saat ini: <a href="{{ asset($item->image) }}" target="_blank" rel="noopener">{{ $item->image }}</a></p>
            @endif
        </div>
        <div class="form-section p-5">
            <x-admin-components::field name="is_active" label="Tampilkan di website" type="checkbox" :value="$item->is_active ?? true" />
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.leaders.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Data' }}
    </button>
</div>