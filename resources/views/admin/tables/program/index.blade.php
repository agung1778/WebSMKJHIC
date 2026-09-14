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
        <x-admin-components::table
            :head="['Program', 'Status', 'Penerbit', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari program…"
            :category-options="['' => 'Semua status', 'published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived']">
            <x-slot:body>
                @forelse ($programs as $p)
                    @php $programStatus = $p->status ?? 'published'; @endphp
                    <tr data-row data-cat="{{ $programStatus }}">
                        <td data-label="Program">
                            <div class="table-main">
                                <img class="thumb" src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $p->name }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($p->description), 110) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Status"><span class="badge {{ ['published' => 'badge-published', 'draft' => 'badge-draft', 'archived' => 'badge-archived'][$programStatus] ?? 'badge-archived' }}">{{ ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'][$programStatus] ?? $programStatus }}</span></td>
                        <td data-label="Penerbit"><span style="white-space:nowrap">{{ $p->publisher }}</span></td>
                        <td data-label="Diperbarui"><span style="white-space:nowrap" class="cell-sub">{{ \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.programs.show', $p) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.programs.edit', $p) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.programs.duplicate', $p) }}" method="POST" onsubmit="return confirm('Duplikat program {{ addslashes($p->name) }}?')">
                                    @csrf
                                    <button class="act-btn" type="submit" title="Duplikat"><i class="fa-solid fa-copy"></i></button>
                                </form>
                                @if ($programStatus !== 'published')
                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="published">
                                        <button class="act-btn publish" type="submit" title="Publish"><i class="fa-solid fa-cloud-arrow-up"></i></button>
                                    </form>
                                @endif
                                @if ($programStatus !== 'archived')
                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="archived">
                                        <button class="act-btn" type="submit" title="Arsipkan"><i class="fa-solid fa-box-archive"></i></button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.programs.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus program ini? Tindakan ini tidak dapat dibatalkan.')">
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
                            <x-admin-components::empty-state
                                icon="fa-solid fa-graduation-cap"
                                title="Belum ada program"
                                description="Mulai tambahkan program pendidikan pertama untuk ditampilkan di website sekolah."
                                action-label="Tambah Program"
                                action-url="{{ route('admin.programs.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection