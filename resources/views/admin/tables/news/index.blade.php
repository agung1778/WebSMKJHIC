@extends('layouts.admin-app')

@section('title', 'Berita')

@section('content')
    @php
        $total = $news->count();
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Kelola konten website"
            title="Berita"
            subtitle="Kelola berita dan informasi terbaru yang tampil di website sekolah.">
            <x-slot:actions>
                <a class="app-btn app-btn-primary app-btn-lg" href="{{ route('admin.news.create') }}">
                    <i class="fa-solid fa-plus"></i><span class="hide-mob">Tulis Berita</span>
                </a>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Ringkasan --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin-components::stat-card label="Total Berita" :value="$total" icon="fa-solid fa-newspaper" tone="brand" />
            <x-admin-components::stat-card label="Diterbitkan Bulan Ini" :value="$news->where('date_published','>=', now()->startOfMonth())->count()" icon="fa-solid fa-calendar-check" tone="green" />
            <x-admin-components::stat-card label="Kontributor" :value="$news->pluck('publisher')->unique()->count()" icon="fa-solid fa-user-pen" tone="blue" />
            <x-admin-components::stat-card label="Terakhir Diperbarui" :value="$news->max('updated_at') ? \Carbon\Carbon::parse($news->max('updated_at'))->locale('id')->diffForHumans() : '-'" icon="fa-solid fa-clock-rotate-left" tone="amber" />
        </div>

        {{-- Tabel --}}
        <x-admin-components::table
            :head="['Berita', 'Tanggal Terbit', 'Penerbit', ['label' => 'Aksi', 'right' => true]]"
            search-hint="Cari berita, penerbit…"
            :export-url="route('admin.export', ['resource' => 'news'])">
            <x-slot:body>
                @forelse ($news as $n)
                    <tr data-row>
                        <td data-label="Berita">
                            <div class="table-main">
                                <img class="thumb" src="{{ $n->image ? asset('storage/' . $n->image) : 'https://placehold.co/64x64/eff9e3/63cd00?text=BRT' }}" alt="{{ $n->title }}" loading="lazy">
                                <div style="min-width:0">
                                    <div class="cell-main truncate">{{ $n->title }}</div>
                                    <div class="cell-sub truncate">{{ \Illuminate\Support\Str::limit(strip_tags($n->description), 150) }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Tanggal Terbit"><span class="cell-sub" style="white-space:nowrap">{{ \Carbon\Carbon::parse($n->date_published)->translatedFormat('d M Y') }}</span></td>
                        <td data-label="Penerbit"><span style="white-space:nowrap"><i class="fa-regular fa-user mr-1" style="color:var(--text-3)"></i>{{ $n->publisher }}</span></td>
                        <td data-label="Aksi" class="text-right">
                            <div class="table-actions">
                                <a class="act-btn view" href="{{ route('admin.news.show', $n) }}" title="Lihat detail"><i class="fa-regular fa-eye"></i></a>
                                <a class="act-btn edit" href="{{ route('admin.news.edit', $n) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.news.destroy', $n) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="act-btn delete" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty>
                        <td colspan="4">
                            <x-admin-components::empty-state
                                icon="fa-solid fa-newspaper"
                                title="Belum ada berita"
                                description="Tulis berita pertama untuk ditampilkan di halaman website sekolah."
                                action-label="Tulis Berita"
                                action-url="{{ route('admin.news.create') }}" />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection