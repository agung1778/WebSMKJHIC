@extends('layouts.admin-app')

@section('title', 'Feed TikTok')

@section('content')
    @php
        $activeCount = $posts->where('is_active', true)->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-brands fa-tiktok"
            kicker="Website"
            title="Feed TikTok"
            subtitle="Kelola postingan video TikTok yang ditampilkan di homepage website sekolah. Unggah foto cover dan tempel tautan video TikTok. Maksimal 18 postingan." />

        @if (session('error'))
            <div class="p-4 rounded-xl text-sm font-medium border bg-red-50 text-red-700 border-red-200">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <x-admin-components::stat-card label="Total Video TikTok" :value="$posts->count()" icon="fa-brands fa-tiktok" tone="brand" />
            <x-admin-components::stat-card label="Aktif di Website" :value="$activeCount" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Tersembunyi" :value="$posts->where('is_active', false)->count()" icon="fa-solid fa-eye-slash" tone="amber" />
        </div>

        <div class="admin-media-layout grid gap-6">
            {{-- Form Tambah Video TikTok --}}
            <div class="app-card p-0 h-fit lg:sticky lg:top-24">
                <div class="p-5 border-b" style="border-color:var(--border)">
                    <h3 class="card-title"><i class="fa-brands fa-tiktok text-[var(--brand)]"></i> Tambah Video TikTok</h3>
                    <p class="text-[12.5px]" style="color:var(--text-3)">PNG, JPG, JPEG, WEBP (Maks. 5MB)</p>
                </div>
                <form action="{{ route('admin.tiktok-posts.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="app-label">Cover Thumbnail Video <span class="req">*</span></label>
                        <x-admin-components::dropzone name="image_file" :required="true" accept="image/*" hint="Klik untuk memilih foto cover video." />
                    </div>
                    <div>
                        <label class="app-label" for="caption">Judul / Caption <span class="optional">(opsional)</span></label>
                        <textarea name="caption" id="caption" rows="2" class="app-textarea" placeholder="Tulis judul atau caption video TikTok...">{{ old('caption') }}</textarea>
                    </div>
                    <div>
                        <label class="app-label" for="video_url">Link Video TikTok <span class="optional">(opsional)</span></label>
                        <x-admin-components::field name="video_url" placeholder="https://www.tiktok.com/@smk.amaliah/video/..." icon="fa-brands fa-tiktok" :value="old('video_url')" />
                    </div>
                    <div>
                        <label class="app-label" for="video_id">ID Video TikTok <span class="optional">(opsional, otomatis jika link diisi)</span></label>
                        <x-admin-components::field name="video_id" placeholder="Contoh: 7701197482784541973" icon="fa-solid fa-hashtag" :value="old('video_id')" />
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
                        <i class="fa-solid fa-plus"></i> Tambah Video TikTok
                    </button>
                </form>
            </div>

            {{-- Daftar Postingan TikTok --}}
            <div>
                <div data-filter-root class="app-card">
                    <div class="toolbar">
                        <div class="search-field">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input class="app-input" type="search" placeholder="Cari judul, link…" data-filter-input>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Judul &amp; Link</th>
                                    <th>Status</th>
                                    <th>Ditambahkan</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $p)
                                    @php
                                        $caption = $p->caption ?: 'Video TikTok SMK Amaliah';
                                        $active = (bool) $p->is_active;
                                        $imgUrl = asset('storage/' . $p->path);
                                    @endphp
                                    <tr data-row>
                                        <td data-label="Cover">
                                            <div class="relative w-14 h-14 rounded-xl overflow-hidden border border-gray-200 cursor-zoom-in group" onclick="window.open('{{ $imgUrl }}')">
                                                <img class="w-full h-full object-cover" src="{{ $imgUrl }}" alt="{{ $caption }}" loading="lazy">
                                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i class="fa-solid fa-play"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Judul & Link">
                                            <div class="cell-main truncate" style="max-width:280px">{{ $caption }}</div>
                                            @if ($p->video_url)
                                                <a class="cell-sub truncate d-block" style="max-width:280px;color:var(--blue,#1d6fd6)" href="{{ $p->video_url }}" target="_blank" rel="noopener">
                                                    <i class="fa-brands fa-tiktok text-xs mr-1"></i>{{ $p->video_url }}
                                                </a>
                                            @elseif ($p->video_id)
                                                <span class="cell-sub text-xs text-gray-500">ID: {{ $p->video_id }}</span>
                                            @endif
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge {{ $active ? 'badge-published' : 'badge-archived' }}">
                                                {{ $active ? 'Aktif' : 'Tersembunyi' }}
                                            </span>
                                        </td>
                                        <td data-label="Ditambahkan">
                                            <span class="cell-sub">{{ $p->created_at->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="table-actions flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.tiktok-posts.toggle', $p) }}" method="POST" title="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                    @csrf
                                                    <button class="icon-btn" type="submit" aria-label="{{ $active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                        <i class="fa-solid {{ $active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    </button>
                                                </form>
                                                <button class="icon-btn js-edit-tiktok" type="button"
                                                    title="Edit"
                                                    data-action="{{ route('admin.tiktok-posts.update', $p) }}"
                                                    data-img="{{ $imgUrl }}"
                                                    data-caption="{{ $p->caption ?? '' }}"
                                                    data-url="{{ $p->video_url ?? '' }}"
                                                    data-id="{{ $p->video_id ?? '' }}"
                                                    data-active="{{ $active ? '1' : '0' }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('admin.tiktok-posts.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus video TikTok ini?')">
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
                                                    <div class="empty-icon"><i class="fa-brands fa-tiktok"></i></div>
                                                    <h3>Belum ada video TikTok</h3>
                                                    <p>Tambahkan video TikTok pertama untuk ditampilkan di halaman depan website.</p>
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

        {{-- Modal Edit Video TikTok --}}
        <div class="modal-overlay" id="editTiktokModal" style="display:none">
            <div class="modal-card" style="max-width:480px">
                <form action="" method="POST" enctype="multipart/form-data" id="editTiktokForm" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="flex items-center justify-between">
                        <h3 class="card-title"><i class="fa-regular fa-pen-to-square text-[var(--brand)]"></i> Edit Video TikTok</h3>
                        <button class="icon-btn js-edit-close" type="button" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="flex items-center gap-4">
                        <img id="editTiktokImg" alt="Pratinjau" class="rounded-xl border" style="width:72px;height:72px;object-fit:cover;border-color:var(--border)">
                        <div class="flex-1">
                            <label class="app-label">Ganti Cover <span class="optional">(opsional)</span></label>
                            <input type="file" name="image_file" accept="image/*" class="app-file">
                        </div>
                    </div>
                    <div>
                        <label class="app-label" for="edit_caption">Judul / Caption</label>
                        <textarea name="caption" id="edit_caption" rows="2" class="app-textarea"></textarea>
                    </div>
                    <div>
                        <label class="app-label" for="edit_video_url">Link Video TikTok</label>
                        <input type="url" name="video_url" id="edit_video_url" class="app-input" placeholder="https://www.tiktok.com/@smk.amaliah/video/...">
                    </div>
                    <div>
                        <label class="app-label" for="edit_video_id">ID Video TikTok</label>
                        <input type="text" name="video_id" id="edit_video_id" class="app-input" placeholder="Contoh: 7701197482784541973">
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
                var modal = document.getElementById('editTiktokModal');
                var form = document.getElementById('editTiktokForm');
                if (!modal || !form) return;

                function openEdit(btn) {
                    form.action = btn.getAttribute('data-action') || '';
                    document.getElementById('editTiktokImg').src = btn.getAttribute('data-img') || '';
                    document.getElementById('edit_caption').value = btn.getAttribute('data-caption') || '';
                    document.getElementById('edit_video_url').value = btn.getAttribute('data-url') || '';
                    document.getElementById('edit_video_id').value = btn.getAttribute('data-id') || '';
                    document.getElementById('edit_active').checked = btn.getAttribute('data-active') === '1';
                    modal.style.display = 'flex';
                }

                function closeEdit() {
                    modal.style.display = 'none';
                }

                document.querySelectorAll('.js-edit-tiktok').forEach(function (btn) {
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
