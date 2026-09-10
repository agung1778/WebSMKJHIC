@php
    $item = $partner ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Mitra</h4>
            <div class="form-grid-2">
                <div>
                    <x-admin-components::field name="name" label="Nama Perusahaan / Institusi" :required="true" :value="$item->name ?? null"
                        placeholder="Contoh: PT Teknologi Nusantara" icon="fa-solid fa-building" />
                </div>
                <div>
                    <x-admin-components::field name="sector" label="Sektor / Bidang" :value="$item->sector ?? null"
                        placeholder="Contoh: Teknologi Informasi" icon="fa-solid fa-industry" />
                </div>
                <div>
                    <x-admin-components::field name="city" label="Kota" :value="$item->city ?? null"
                        placeholder="Contoh: Bogor" icon="fa-solid fa-location-dot" />
                </div>
                <div>
                    <x-admin-components::field name="partnership_date" label="Tanggal Kerja Sama" :required="true" type="date"
                        :value="$item && $item->partnership_date ? \Carbon\Carbon::parse($item->partnership_date)->format('Y-m-d') : null" />
                </div>
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="company_contact" label="Kontak Perusahaan" :value="$item->company_contact ?? null"
                        placeholder="Email / telepon / website mitra" icon="fa-solid fa-address-book" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
            <x-admin-components::field name="description" label="Deskripsi Mitra" :required="true" type="trix" :value="$item->description ?? null" />
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-fill-drip" style="color:var(--brand)"></i> Logo Mitra</h4>
        <x-admin-components::dropzone name="logo" label="Logo Perusahaan" :required="!$item" :current="$item->logo ?? null"
            hint="Gambar logo dengan latar transparan. Maks. 2MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.partners.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Mitra' }}
    </button>
</div>