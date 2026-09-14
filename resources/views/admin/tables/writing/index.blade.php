@extends('layouts.admin-app')

@section('title', 'Home & Tulisan')

@section('content')
    @php
        $total = $writings->count();
        $wordCount = $writings->sum(fn ($w) => str_word_count(strip_tags($w->content ?? '')));
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Website"
            title="Home & Tulisan"
            subtitle="Kelola tulisan dan konten halaman beranda website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.writings.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Baru</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Tulisan" :value="$total" icon="fa-solid fa-pen-nib" tone="brand" />
            <x-admin-components::stat-card label="Total Kata" :value="number_format($wordCount)" icon="fa-solid fa-font" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$writings->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$writings->max('updated_at') ? \Carbon\Carbon::parse($writings->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        <x-admin-components::table
            :head="['Judul', 'Terbit', 'Penerbit', 'Diperbarui', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari judul, penerbit…"
            :export-url="route('admin.export', ['resource' => 'writings'])">
            <x-slot:body>
                @forelse ($writings as $w)
                    <tr data-row>
                        <td data-label="Judul">
                            <div style="min-width:0">
                                <div class="cell-main truncate">{{ $w->title }}</div>
                                <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($w->content ?? ''), 160) }}</div>
                            </div>
                        </td>
                        <td data-label="Terbit"><span class="badge badge-published" style="white-space:nowrap">{{ $w->release_date ? \Carbon\Carbon::parse($w->release_date)->translatedFormat('d M Y') : '-' }}</span></td>
                        <td data-label="Penerbit"><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $w->publisher ?? '-' }}</span></td>
                        <td data-label="Diperbarui"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($w->updated_at)->locale('id')->diffForHumans() }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.writings.show', $w) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.writings.edit', $w) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.writings.destroy', $w) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tulisan ini?')">
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
                                icon="fa-solid fa-pen-nib"
                                title="Belum ada tulisan"
                                description="Mulai tulis konten pertama untuk halaman home & tulisan website."
                                action-label="Tulis Baru"
                                action-url="{{ route('admin.writings.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection