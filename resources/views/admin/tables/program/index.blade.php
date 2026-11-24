@extends('layouts.admin-app')

@section('title', 'Program Sekolah')

@section('content')
    @php
        $total = $programs->count();
        $published = $programs->where('status', 'published')->count();
        $drafts = $programs->where('status', 'draft')->count();
        $archived = $programs->where('status', 'archived')->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Konten"
            title="Program Sekolah"
            subtitle="Kelola program keahlian, program unggulan, dan kurikulum pendidikan yang ditampilkan di website sekolah.">
            <x-slot:actions>
                <a href="{{ route('admin.programs.create') }}" class="app-btn app-btn-primary app-btn-lg">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tambah Program</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 xl:grid-cols-4 stat-grid-2">
            <x-admin-components::stat-card label="Total Program" :value="$total" icon="fa-solid fa-graduation-cap" tone="brand" />
            <x-admin-components::stat-card label="Published" :value="$published" icon="fa-solid fa-circle-check" tone="green" />
            <x-admin-components::stat-card label="Draft" :value="$drafts" icon="fa-solid fa-pen-ruler" tone="amber" />
            <x-admin-components::stat-card label="Archived" :value="$archived" icon="fa-solid fa-box-archive" tone="dark" />
        </div>

        {{-- Card utama --}}
        <x-admin-components::table
            :head="['Program', 'Status', ['label' => 'Penerbit', 'class' => 'hide-mob'], ['label' => 'Diperbarui', 'class' => 'hide-mob'], ['label' => 'Aksi', 'right' => true, 'class' => 'has-3-actions']]"
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
                                    <div class="cell-main truncate font-medium">{{ $p->name }}</div>
                                    <div class="cell-sub truncate text-xs">{{ \Illuminate\Support\Str::limit(strip_tags($p->description), 110) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Status"><span class="badge {{ ['published' => 'badge-published', 'draft' => 'badge-draft', 'archived' => 'badge-archived'][$programStatus] ?? 'badge-archived' }}">{{ ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'][$programStatus] ?? $programStatus }}</span></td>
                        <td data-label="Penerbit" class="hide-mob"><span style="white-space:nowrap">{{ $p->publisher }}</span></td>
                        <td data-label="Diperbarui" class="hide-mob"><span style="white-space:nowrap" class="cell-sub">{{ \Carbon\Carbon::parse($p->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="has-3-actions text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.programs.show', $p) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.programs.edit', $p) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.programs.duplicate', $p) }}" method="POST" onsubmit="return confirm('Duplikat program {{ addslashes($p->name) }}?')" class="inline-block hide-mob">
                                    @csrf
                                    <button class="act-btn" type="submit" title="Duplikat"><i class="fa-solid fa-copy"></i></button>
                                </form>
                                @if ($programStatus !== 'published')
                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST" class="inline-block hide-mob">
                                        @csrf
                                        <input type="hidden" name="status" value="published">
                                        <button class="act-btn publish" type="submit" title="Publish"><i class="fa-solid fa-cloud-arrow-up"></i></button>
                                    </form>
                                @endif
                                @if ($programStatus !== 'archived')
                                    <form action="{{ route('admin.programs.updateStatus', $p) }}" method="POST" class="inline-block hide-mob">
                                        @csrf
                                        <input type="hidden" name="status" value="archived">
                                        <button class="act-btn" type="submit" title="Arsipkan"><i class="fa-solid fa-box-archive"></i></button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.programs.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus program ini? Tindakan ini tidak dapat dibatalkan.')" class="inline-block">
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