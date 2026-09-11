@extends('layouts.admin-app')

@section('title', 'Educational Programs')

@section('content')
    @php
        $total = $programs->count();
        $published = $programs->where('status', 'published')->count();
        $drafts = $programs->where('status', 'draft')->count();
        $archived = $programs->where('status', 'archived')->count();
    @endphp

    <div class="animate-fadein space-y-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-end justify-between gap-4 mb-2">
            <div>
                <h2 class="text-[22px] font-extrabold" style="color:var(--text)">Educational Programs</h2>
                <p class="text-[13px] mt-1" style="color:var(--text-3)">Kelola program pendidikan yang ditampilkan di website sekolah.</p>
            </div>
            <a href="{{ route('admin.programs.create') }}" class="app-btn app-btn-primary app-btn-lg">
                <i class="fa-solid fa-plus"></i> Tambah Program
            </a>
        </div>

        {{-- Mini stats --}}
        <div class="mini-stats">
            <div class="mini-stat">
                <div class="ms-label">Total Program</div>
                <div class="ms-value">{{ $total }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Published</div>
                <div class="ms-value" style="color:var(--green)">{{ $published }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Draft</div>
                <div class="ms-value" style="color:var(--amber)">{{ $drafts }}</div>
            </div>
            <div class="mini-stat">
                <div class="ms-label">Archived</div>
                <div class="ms-value" style="color:var(--slate)">{{ $archived }}</div>
            </div>
        </div>

        {{-- Card utama --}}
        <div data-filter-root>
            {{-- Toolbar --}}
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari program…" data-filter-input>
                </div>

                <select class="app-select" style="width:auto" data-filter-cat>
                    <option value="">Semua status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>

                <span class="toolbar-spacer"></span>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Penerbit</th>
                            <th>Diperbarui</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $p)
                            @php $programStatus = $p->status ?? 'published'; @endphp
                            <tr data-row data-cat="{{ $programStatus }}">
                                <td>
                                    <div class="flex items-center gap-3" style="min-width:240px">
                                        <img class="thumb" src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" loading="lazy">
                                        <div style="min-width:0">
                                            <div class="cell-main truncate">{{ $p->name }}</div>
                                            <div class="cell-sub truncate" style="max-width:320px">{{ \Illuminate\Support\Str::limit(strip_tags($p->description), 110) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ ['published' => 'badge-published', 'draft' => 'badge-draft', 'archived' => 'badge-archived'][$programStatus] ?? 'badge-archived' }}">{{ ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'][$programStatus] ?? $programStatus }}</span></td>
                                <td><span style="white-space:nowrap">{{ $p->publisher }}</span></td>
                                <td><span style="white-space:nowrap" class="cell-sub">{{ \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</span></td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a class="app-btn app-btn-sm app-btn-primary" href="{{ route('admin.programs.edit', $p) }}"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                        <div class="dropdown">
                                            <button class="app-btn app-btn-sm" type="button" data-dropdown aria-label="Aksi lainnya"><i class="fa-solid fa-ellipsis"></i></button>
                                            <div class="dropdown-menu" style="display:none">
                                                <a class="dropdown-item" href="{{ route('admin.programs.show', $p) }}"><i class="fa-regular fa-eye"></i><span>Lihat detail</span></a>
                                                <form action="{{ route('admin.programs.duplicate', $p) }}" method="POST" onsubmit="return confirm('Duplikat program {{ addslashes($p->name) }}?')">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit"><i class="fa-solid fa-copy"></i><span>Duplikat</span></button>
                                                </form>
                                                @if ($programStatus !== 'published')
                                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="published">
                                                        <button class="dropdown-item" type="submit"><i class="fa-solid fa-cloud-arrow-up"></i><span>Publish</span></button>
                                                    </form>
                                                @endif
                                                @if ($programStatus !== 'archived')
                                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="archived">
                                                        <button class="dropdown-item" type="submit"><i class="fa-solid fa-box-archive"></i><span>Arsipkan</span></button>
                                                    </form>
                                                @endif
                                                <div class="dropdown-sep"></div>
                                                <form action="{{ route('admin.programs.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus program ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item danger" type="submit"><i class="fa-solid fa-trash"></i><span>Hapus</span></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr data-row data-empty>
                                <td colspan="5">
                                    <div class="p-6 text-center">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                            <h3>Belum ada program</h3>
                                            <p>Mulai tambahkan program pendidikan pertama untuk ditampilkan di website sekolah.</p>
                                            <a class="app-btn app-btn-primary" href="{{ route('admin.programs.create') }}"><i class="fa-solid fa-plus"></i>Tambah Program</a>
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
@endsection