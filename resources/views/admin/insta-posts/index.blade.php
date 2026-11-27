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
            subtitle="Postingan terbaru tersinkron otomatis dari Instagram jika API terhubung. Cukup tempel link postingan — foto dan caption akan diambil otomatis. Unggah manual tetap tersedia. Maksimal 9 postingan aktif; data terlama otomatis diganti saat kuota penuh." />

        @if (session('error'))
            <div class="p-4 rounded-xl text-sm font-medium border bg-red-50 text-red-700 border-red-200">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 xl:grid-cols-4 stat-grid-2">
            <x-admin-components::stat-card label="Total Postingan" :value="$posts->count()" icon="fa-brands fa-instagram" tone="brand" />
            <x-admin-components::stat-card label="Aktif di Website" :value="$activeCount" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Tersembunyi" :value="$posts->where('is_active', false)->count()" icon="fa-solid fa-eye-slash" tone="amber" />
            <x-admin-components::stat-card label="Kuota" :value="$posts->count() . ' / 9'" icon="fa-solid fa-gauge-high" tone="blue" />
        </div>

        <div class="admin-media-layout grid gap-6">
            {{-- Form Tambah Postingan Instagram --}}
            <div class="app-card p-0 h-fit lg:sticky lg:top-24">
                <div class="p-4 sm:p-5 border-b" style="border-color:var(--border)">
                    <h3 class="card-title"><i class="fa-brands fa-instagram text-[var(--brand)]"></i> Tambah Postingan</h3>
                    <p class="text-[12.5px]" style="color:var(--text-3)">PNG, JPG, JPEG, WEBP, AVIF (Maks. 5MB)</p>
                </div>
                <form action="{{ route('admin.insta-posts.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="app-label" for="post_url">Link Postingan Instagram <span class="req">*</span></label>
                        <x-admin-components::field name="post_url" placeholder="https://www.instagram.com/p/..." icon="fa-brands fa-instagram" :value="old('post_url')" />
                        <p class="text-[11px] mt-1.5" style="color:var(--text-3)">Tempel link postingan — foto & caption akan diambil otomatis dari Instagram.</p>
                    </div>
                    <div>
                        <label class="app-label" for="caption">Caption <span class="optional">(opsional, otomatis dari Instagram)</span></label>
                        <textarea name="caption" id="caption" rows="3" class="app-textarea" placeholder="Kosongkan untuk memakai caption dari Instagram...">{{ old('caption') }}</textarea>
                    </div>
                    <div>
                        <label class="app-label">Foto Postingan <span class="optional">(opsional, otomatis dari link)</span></label>
                        <x-admin-components::dropzone name="image_file" :required="false" accept="image/*" hint="Biarkan kosong untuk mengambil foto otomatis dari link postingan." />
                    </div>
                    <div>
                        <label class="app-label" for="instagram_media_id">ID / Shortcode Media <span class="optional">(opsional, otomatis jika link diisi)</span></label>
                        <x-admin-components::field name="instagram_media_id" placeholder="Contoh: DAGw8pKxy7z" icon="fa-solid fa-hashtag" :value="old('instagram_media_id')" />
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

            {{-- Daftar Postingan Instagram --}}
            <div>
                <div data-filter-root class="app-card">
                    <div class="toolbar">
                        <div class="search-field">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input class="app-input" type="search" placeholder="Cari caption, link…" data-filter-input>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th data-col="pratinjau" class="w-14">Pratinjau</th>
                                    <th>Caption & Link</th>
                                    <th>Status</th>
                                    <th class="hide-mob" data-col="ditambahkan">Ditambahkan</th>
                                    <th class="text-right has-3-actions" data-col="aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $p)
                                    @php
                                        $caption = $p->caption ?: 'Postingan Instagram SMK Amaliah';
                                        $active = (bool) $p->is_active;
                                        $imgUrl = asset('storage/' . $p->path);
                                    @endphp
                                    <tr data-row>
                                        <td data-label="Pratinjau">
                                            <div class="relative w-11 h-11 rounded-lg overflow-hidden border border-gray-200 cursor-zoom-in group" onclick="window.open('{{ $imgUrl }}')">
                                                <img class="w-full h-full object-cover" src="{{ $imgUrl }}" alt="{{ $caption }}" loading="lazy">
                                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-[10px] opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i class="fa-brands fa-instagram"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Caption">
                                            <div class="cell-main truncate max-w-[130px] sm:max-w-[280px] font-medium">{{ $caption }}</div>
                                            @if ($p->post_url)
                                                <a class="cell-sub truncate d-block max-w-[130px] sm:max-w-[280px]" style="color:var(--blue,#1d6fd6)" href="{{ $p->post_url }}" target="_blank" rel="noopener">
                                                    <i class="fa-brands fa-instagram text-xs mr-1"></i>{{ $p->post_url }}
                                                </a>
                                            @elseif ($p->instagram_media_id)
                                                <span class="cell-sub text-xs text-gray-500">ID: {{ $p->instagram_media_id }}</span>
                                            @endif
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge {{ $active ? 'badge-published' : 'badge-archived' }}">
                                                {{ $active ? 'Aktif' : 'Tersembunyi' }}
                                            </span>
                                        </td>
                                        <td data-label="Ditambahkan" class="hide-mob">
                                            <span class="cell-sub">{{ $p->created_at->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td data-label="Aksi" class="has-3-actions text-right">
                                            <div class="table-actions flex items-center justify-end gap-1">
                                                <form action="{{ route('admin.insta-posts.toggle', $p) }}" method="POST" title="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}" class="inline-block">
                                                    @csrf
                                                    <button class="act-btn view" type="submit" aria-label="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                        <i class="fa-solid {{ $active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    </button>
                                                </form>
                                                <button class="act-btn edit js-edit-insta" type="button"
                                                    title="Edit"
                                                    data-action="{{ route('admin.insta-posts.update', $p) }}"
                                                    data-img="{{ $imgUrl }}"
                                                    data-caption="{{ $p->caption ?? '' }}"
                                                    data-url="{{ $p->post_url ?? '' }}"
                                                    data-id="{{ $p->instagram_media_id ?? '' }}"
                                                    data-active="{{ $active ? '1' : '0' }}">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form action="{{ route('admin.insta-posts.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus postingan Instagram ini?')" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
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
                                                    <h3>Belum ada postingan Instagram</h3>
                                                    <p>Tempel link postingan Instagram pertama untuk ditampilkan di halaman depan website.</p>
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

        {{-- Modal Edit Postingan Instagram --}}
        <div class="modal-overlay" id="editInstaModal" style="display:none">
            <div class="modal-card w-full mx-3 sm:mx-auto" style="max-width:480px">
                <form action="" method="POST" enctype="multipart/form-data" id="editInstaForm" class="p-4 sm:p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="flex items-center justify-between">
                        <h3 class="card-title"><i class="fa-regular fa-pen-to-square text-[var(--brand)]"></i> Edit Postingan</h3>
                        <button class="icon-btn js-edit-close" type="button" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="flex items-center gap-4">
                        <img id="editInstaImg" alt="Pratinjau" class="rounded-xl border shrink-0" style="width:64px;height:64px;object-fit:cover;border-color:var(--border)">
                        <div class="flex-1 min-w-0">
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
                    <div>
                        <label class="app-label" for="edit_media_id">ID / Shortcode Media</label>
                        <input type="text" name="instagram_media_id" id="edit_media_id" class="app-input" placeholder="Contoh: DAGw8pKxy7z">
                    </div>
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                        <span class="toggle-switch">
                            <input type="checkbox" value="1" id="edit_active" name="is_active" checked>
                            <span class="track"></span>
                        </span>
                        <span class="text-sm font-medium" style="color:var(--text-2)">Tampilkan di website</span>
                    </label>
                    <div class="modal-foot flex justify-end gap-2 pt-2 border-t" style="border-color:var(--border)">
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
                    document.getElementById('edit_media_id').value = btn.getAttribute('data-id') || '';
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
