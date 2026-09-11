@extends('layouts.admin-app')

@section('title', 'Feed Instagram')

@section('content')
    @php
        $activeCount = $posts->where('is_active', true)->count();
    @endphp

    <div class="fade-up space-y-6">

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
            <div>
                <div data-filter-root class="app-card overflow-hidden">
                    <div class="toolbar">
                        <div class="search-field">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input class="app-input" type="search" placeholder="Cari caption, link…" data-filter-input>
                        </div>
                        <span class="toolbar-spacer"></span>
                    </div>

                    <div class="table-wrap">
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
                                @forelse ($posts as $p)
                                    @php
                                        $caption = $p->caption ?: 'Tanpa caption';
                                        $active = (bool) $p->is_active;
                                        $imgUrl = asset('storage/' . $p->path);
                                    @endphp
                                    <tr data-row>
                                        <td>
                                            <img class="thumb cursor-zoom-in" src="{{ $imgUrl }}" alt="{{ $caption }}" loading="lazy" style="width:56px;height:56px;border-radius:14px" onclick="window.open('{{ $imgUrl }}')">
                                        </td>
                                        <td style="min-width:220px">
                                            <div class="cell-main truncate" style="max-width:280px">{{ $caption }}</div>
                                            @if ($p->post_url)
                                                <a class="cell-sub truncate d-block" style="max-width:280px;color:var(--blue,#1d6fd6)" href="{{ $p->post_url }}" target="_blank">{{ $p->post_url }}</a>
                                            @endif
                                        </td>
                                        <td><span class="badge {{ $active ? 'badge-published' : 'badge-archived' }}">{{ $active ? 'Aktif' : 'Tersembunyi' }}</span></td>
                                        <td><span class="cell-sub" style="white-space:nowrap">{{ $p->created_at->translatedFormat('d M Y') }}</span></td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.insta-posts.toggle', $p) }}" method="POST" title="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                    @csrf
                                                    <button class="icon-btn" type="submit" aria-label="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                        <i class="fa-solid {{ $active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    </button>
                                                </form>
                                                <button class="icon-btn js-edit-insta" type="button"
                                                    title="Edit"
                                                    data-action="{{ route('admin.insta-posts.update', $p) }}"
                                                    data-img="{{ $imgUrl }}"
                                                    data-caption="{{ $p->caption ?? '' }}"
                                                    data-url="{{ $p->post_url ?? '' }}"
                                                    data-active="{{ $active ? '1' : '0' }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('admin.insta-posts.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus postingan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="icon-btn danger" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr data-row data-empty>
                                        <td colspan="5">
                                            <div class="p-6 text-center">
                                                <div class="empty-state">
                                                    <div class="empty-icon"><i class="fa-brands fa-instagram"></i></div>
                                                    <h3>Belum ada postingan</h3>
                                                    <p>Unggah foto postingan Instagram pertama untuk ditampilkan di website.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('admin.tables._filter')
                </div>
            </div>
        </div>

        {{-- Modal edit --}}
        <div class="modal-overlay" id="editInstaModal" style="display:none">
            <div class="modal-card" style="max-width:480px">
                <form action="" method="POST" enctype="multipart/form-data" id="editInstaForm" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="flex items-center justify-between">
                        <h3 class="card-title"><i class="fa-regular fa-pen-to-square text-[var(--brand)]"></i> Edit Postingan</h3>
                        <button class="icon-btn js-edit-close" type="button" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="flex items-center gap-4">
                        <img id="editInstaImg" alt="Pratinjau" class="rounded-xl border" style="width:72px;height:72px;object-fit:cover;border-color:var(--border)">
                        <div class="flex-1">
                            <label class="app-label">Ganti Foto <span class="optional">(opsional)</span></label>
                            <input type="file" name="image_file" accept="image/*" class="app-file">
                        </div>
                    </div>
                    <div>
                        <label class="app-label" for="edit_caption">Caption</label>
                        <textarea name="caption" id="edit_caption" rows="3" class="app-textarea"></textarea>
                    </div>
                    <div>
                        <label class="app-label" for="edit_post_url">Link Postingan</label>
                        <input type="url" name="post_url" id="edit_post_url" class="app-input" placeholder="https://www.instagram.com/p/...">
                    </div>
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                        <span class="toggle-switch">
                            <input type="checkbox" value="1" id="edit_active" name="is_active" checked>
                            <span class="track"></span>
                        </span>
                        <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan di website</span>
                    </label>
                    <div class="flex justify-end gap-2 pt-2 border-t" style="border-color:var(--border)">
                        <button type="button" class="app-btn js-edit-close">Batal</button>
                        <button type="submit" class="app-btn app-btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                var modal = document.getElementById('editInstaModal');
                var form = document.getElementById('editInstaForm');
                if (!modal || !form) return;

                function openEdit(btn) {
                    form.action = btn.getAttribute('data-action') || '';
                    document.getElementById('editInstaImg').src = btn.getAttribute('data-img') || '';
                    document.getElementById('edit_caption').value = btn.getAttribute('data-caption') || '';
                    document.getElementById('edit_post_url').value = btn.getAttribute('data-url') || '';
                    document.getElementById('edit_active').checked = btn.getAttribute('data-active') === '1';
                    modal.style.display = 'flex';
                }

                function closeEdit() {
                    modal.style.display = 'none';
                }

                document.querySelectorAll('.js-edit-insta').forEach(function (btn) {
                    btn.addEventListener('click', function () { openEdit(btn); });
                });
                modal.querySelectorAll('.js-edit-close').forEach(function (btn) {
                    btn.addEventListener('click', closeEdit);
                });
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeEdit();
                });
            })();
        </script>
    @endpush
@endsection