@php
    $item = $achievement ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="space-y-6 lg:col-span-2">
        <div class="form-section space-y-5">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail Prestasi</h4>
            <div class="form-grid-2">
                <div style="grid-column:1/-1">
                    <x-admin-components::field name="title" label="Nama Prestasi" :required="true" :value="$item->title ?? null"
                        placeholder="Contoh: Juara 1 LKS Tingkat Provinsi" icon="fa-solid fa-trophy" />
                </div>
                <div>
                    <x-admin-components::field name="category" label="Kategori" :required="true" type="select"
                        :options="['Individual' => 'Individual', 'Institutional' => 'Institutional']" :value="$item->category ?? null" />
                </div>
                <div>
                    <x-admin-components::field name="level" label="Tingkat" :required="true" :value="$item->level ?? null"
                        placeholder="Contoh: Kabupaten, Provinsi, Nasional" icon="fa-solid fa-medal" />
                </div>
                <div>
                    <x-admin-components::field name="winner" label="Pemenang / Penerima" :required="true" :value="$item->winner ?? null"
                        placeholder="Nama siswa atau institusi" icon="fa-solid fa-user-trophy" />
                </div>
                <div>
                    <x-admin-components::field name="date" label="Tanggal Prestasi" :required="true" type="date"
                        :value="$item && $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : null" />
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
            <x-admin-components::field name="description" label="Rincian Prestasi" :required="true" type="trix" :value="$item->description ?? null"
                hint="Ceritakan detail perlombaan, penyelenggara, dan pencapaian." />
        </div>
    </div>

    <div class="form-section space-y-4 h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-image" style="color:var(--brand)"></i> Dokumentasi</h4>
        <x-admin-components::dropzone name="image" label="Foto / Bukti Prestasi" :required="!$item" :current="$item->image ?? null"
            hint="Rasio 4:3 disarankan. Maks. 5MB." />
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.achievements.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Prestasi' }}
    </button>
</div>