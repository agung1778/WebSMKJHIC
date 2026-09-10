@extends('layouts.admin-app')

@section('title', 'Feed Instagram')

@section('content')
    @php
        $activeCount = $posts->where('is_active', true)->count();
        $items = $posts->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->caption ?: 'Tanpa caption',
                'sub' => $p->post_url ?? '',
                'img' => asset('storage/' . $p->path),
                'by' => $p->created_at->translatedFormat('d M Y'),
                'status' => $p->is_active ? 'active' : 'inactive',
                'caption' => $p->caption ?? '',
                'url' => $p->post_url ?? '',
                'path' => $p->path,
                'ts' => $p->created_at->timestamp,
                'updated' => \Carbon\Carbon::parse($p->created_at)->locale('id')->diffForHumans(),
            ];
        })->values();
    @endphp

    <div class="fade-up space-y-6" x-data="instaIndex({
        raw: @json($items),
        statuses: {
            active: { label: 'Aktif', class: 'badge-published' },
            inactive: { label: 'Tersembunyi', class: 'badge-archived' }
        },
        searchKeys: ['name', 'sub', 'by'],
        perPage: 8,
        emptyHead: 'Belum ada postingan',
        emptyBody: 'Unggah foto postingan Instagram pertama untuk ditampilkan di website.'
    })" x-cloak>

        <x-admin-components::page-header
            icon="fa-brands fa-instagram"
            kicker="Website"
            title="Feed Instagram"
            subtitle="Semi-otomatis: unggah foto postingan Instagram yang tampil di website. Maksimal 16 postingan — data tertua otomatis terhapus." />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Postingan" :value="$posts->count()" icon="fa-brands fa-instagram" tone="brand" />
            <x-admin-components::stat-card label="Aktif di Website" :value="$activeCount" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Tersembunyi" :value="$posts->where('is_active', false)->count()" icon="fa-solid fa-eye-slash" tone="amber" />
            <x-admin-components::stat-card label="Kuota" :value="$posts->count() . ' / 16'" icon="fa-solid fa-gauge-high" tone="blue" />
        </div>

        <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
            {{-- Form tambah --}}
            <div class="app-card p-0 h-fit lg:sticky lg:top-24">
                <div class="p-5 border-b" style="border-color:var(--border)">
                    <h3 class="card-title"><i class="fa-brands fa-instagram text-[var(--brand)]"></i> Tambah Postingan</h3>
                    <p class="text-[12.5px]" style="color:var(--text-3)">PNG, JPG, JPEG, WEBP, AVIF (Maks. 5MB)</p>
                </div>
                <form action="{{ route('admin.insta-posts.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="app-label">Foto Postingan <span class="req">*</span></label>
                        <x-admin-components::dropzone name="image_file" :required="true" accept="image/*" hint="Klik untuk memilih atau seret file ke sini." />
                    </div>
                    <div>
                        <label class="app-label" for="caption">Caption <span class="optional">(opsional)</span></label>
                        <textarea name="caption" id="caption" rows="3" class="app-textarea" placeholder="Tulis caption postingan...">{{ old('caption') }}</textarea>
                    </div>
                    <div>
                        <label class="app-label" for="post_url">Link Postingan <span class="optional">(opsional)</span></label>
                        <x-admin-components::field name="post_url" placeholder="https://www.instagram.com/p/..." icon="fa-solid fa-link" :value="old('post_url')" />
                    </div>
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                        <span class="toggle-switch">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="track"></span>
                        </span>
                        <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan langsung di website</span>
                    </label>
                    <button type="submit" class="app-btn app-btn-primary app-btn-lg w-full justify-center">
                        <i class="fa-solid fa-plus"></i> Tambah Postingan
                    </button>
                </form>
            </div>

            {{-- Daftar postingan --}}
            <div class="app-card overflow-hidden">
                <div class="toolbar">
                    <div class="search-field">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input class="app-input" type="search" placeholder="Cari caption, link…" x-model="q">
                    </div>
                    <span class="toolbar-spacer"></span>
                    <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>
                </div>

                <div class="table-wrap" x-show="!loading">
                    <table class="table-app">
                        <thead>
                            <tr>
                                <th>Pratinjau</th>
                                <th>Caption</th>
                                <th>Status</th>
                                <th>Ditambahkan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="p in paged" :key="p.id">
                                <tr>
                                    <td>
                                        <img class="thumb cursor-zoom-in" :src="p.img" :alt="p.name" loading="lazy" style="width:56px;height:56px;border-radius:14px" @click="window.open(p.img)">
                                    </td>
                                    <td style="min-width:220px">
                                        <div class="cell-main truncate" style="max-width:280px" x-text="p.name"></div>
                                        <a class="cell-sub truncate d-block" style="max-width:280px;color:var(--blue,#1d6fd6)" :href="p.url" target="_blank" x-show="p.url" x-text="p.url"></a>
                                    </td>
                                    <td><span class="badge" :class="badgeClass(p.status)" x-text="statusLabel(p.status)"></span></td>
                                    <td><span class="cell-sub" style="white-space:nowrap" x-text="p.by"></span></td>
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="icon-btn" type="button" @click="toggleActive(p)" :title="p.status === 'active' ? 'Sembunyikan' : 'Tampilkan'" :aria-label="p.status === 'active' ? 'Sembunyikan' : 'Tampilkan'">
                                                <i class="fa-solid" :class="p.status === 'active' ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                            <button class="icon-btn" type="button" @click="openEdit(p)" title="Edit"><i class="fa-regular fa-pen-to-square"></i></button>
                                            <button class="icon-btn danger" type="button" @click="askDelete(p, '/admin/insta-posts/' + p.id)" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-show="loading" class="p-5 grid gap-4">
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center gap-4">
                            <div class="skeleton" style="width:56px;height:56px;border-radius:14px"></div>
                            <div style="flex:1">
                                <div class="skeleton" style="height:14px;width:45%;margin-bottom:8px"></div>
                                <div class="skeleton" style="height:11px;width:70%"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!loading && empty">
                    <x-admin-components::empty-state icon="fa-brands fa-instagram" :title="'Belum ada postingan'" description="Unggah foto postingan Instagram pertama untuk ditampilkan di website." />
                </div>

                <x-admin-components::pagination client countLabel="postingan" />
            </div>
        </div>

        {{-- Modal edit --}}
        <div class="app-modal-backdrop" x-show="editOpen" x-cloak x-transition.opacity @click.self="editOpen = false">
            <div class="app-modal" @keydown.escape.window="editOpen = false">
                <div class="flex items-center justify-between p-5 border-b" style="border-color:var(--border)">
                    <h3 class="card-title"><i class="fa-regular fa-pen-to-square text-[var(--brand)]"></i> Edit Postingan</h3>
                    <button class="icon-btn" type="button" @click="editOpen = false" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="'/admin/insta-posts/' + editItem.id" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="flex items-center gap-4">
                        <img :src="editItem.img" alt="Pratinjau" class="rounded-xl border" style="width:72px;height:72px;object-fit:cover;border-color:var(--border)">
                        <div class="flex-1">
                            <label class="app-label">Ganti Foto <span class="optional">(opsional)</span></label>
                            <input type="file" name="image_file" accept="image/*" class="app-file">
                        </div>
                    </div>
                    <div>
                        <label class="app-label" for="edit_caption">Caption</label>
                        <textarea name="caption" id="edit_caption" rows="3" class="app-textarea" x-model="editItem.caption"></textarea>
                    </div>
                    <div>
                        <label class="app-label" for="edit_post_url">Link Postingan</label>
                        <input type="url" name="post_url" id="edit_post_url" class="app-input" placeholder="https://www.instagram.com/p/..." x-model="editItem.url">
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                        <span class="toggle-switch">
                            <input type="checkbox" value="1" x-model.number="editActive" name="is_active">
                            <span class="track"></span>
                        </span>
                        <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan di website</span>
                    </label>
                    <div class="flex justify-end gap-2 pt-2 border-t" style="border-color:var(--border)">
                        <button type="button" class="app-btn" @click="editOpen = false">Batal</button>
                        <button type="submit" class="app-btn app-btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection