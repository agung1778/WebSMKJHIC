@php
    $item = $navigation ?? null;
    $value = fn ($k) => $item->$k ?? null;
@endphp

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="form-section space-y-5 lg:col-span-2">
        <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail Menu</h4>
        <div class="form-grid-2">
            <div style="grid-column:1/-1">
                <x-admin-components::field name="title" label="Label Menu" :required="true" :value="$value('title')"
                    placeholder="Contoh: Info SPMB" icon="fa-solid fa-heading" />
            </div>
            <div style="grid-column:1/-1">
                <x-admin-components::field name="url" label="URL / Link Tujuan" :required="true" :value="$value('url')"
                    placeholder="Contoh: /spmb atau https://link.google.com/.." icon="fa-solid fa-link" />
            </div>
            <div>
                <x-admin-components::field name="position" label="Posisi" :required="true" type="select"
                    :options="['top_bar' => 'Top Bar', 'main_menu' => 'Menu Utama', 'footer' => 'Footer']" :value="$value('position')" />
            </div>
            <div>
                <x-admin-components::field name="type" label="Tampilan" :required="true" type="select"
                    :options="['link' => 'Link (teks)', 'button' => 'Button (tombol)']" :value="$value('type')" />
            </div>
            <div>
                <x-admin-components::field name="target" label="Buka Link di" :required="true" type="select"
                    :options="['_self' => 'Tab yang sama (_self)', '_blank' => 'Tab baru (_blank)']" :value="$value('target')" />
            </div>
            <div>
                <x-admin-components::field name="order" label="Urutan" :required="true" type="number" min="0" :value="$value('order') ?? 0"
                    icon="fa-solid fa-arrow-up-1-9" />
            </div>
        </div>
    </div>

    <div class="form-section h-fit">
        <h4 class="form-section-title"><i class="fa-solid fa-eye" style="color:var(--brand)"></i> Tampilkan</h4>
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
            <span class="toggle-switch">
                <input type="checkbox" name="is_active" value="1" @checked((bool)($item->is_active ?? true))>
                <span class="track"></span>
            </span>
            <span class="text-sm font-medium" style="color:var(--text-2)">Aktifkan menu ini di website</span>
        </label>
        <p class="field-hint">Menu nonaktif disembunyikan dari website tanpa menghapus data.</p>
    </div>
</div>

<div class="form-actions">
    <a class="app-btn app-btn-lg" href="{{ route('admin.navigations.index') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    <button type="submit" class="app-btn app-btn-lg app-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> {{ $item ? 'Simpan Perubahan' : 'Simpan Menu' }}
    </button>
</div>