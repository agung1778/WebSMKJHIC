@extends('layouts.admin-app')
@section('title', 'Unduhan Berkas')

@section('content')
    <div class="space-y-6 fade-up">
        <x-admin-components::page-header icon="fa-solid fa-download" kicker="Website" title="Unduhan Berkas"
            subtitle="Kelola logo sekolah, logo perusahaan, dan contoh laporan PKL. File diunduh dalam kualitas asli.">
            <x-slot:actions>
                <a href="{{ route('public.downloads.index') }}" target="_blank" rel="noopener" class="app-btn"><i class="fa-solid fa-arrow-up-right-from-square"></i> Halaman Publik</a>
                <a href="{{ route('admin.downloads.create') }}" class="app-btn app-btn-primary"><i class="fa-solid fa-plus"></i> Tambah Berkas</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin-components::stat-card label="Total Berkas" :value="$downloads->count()" icon="fa-solid fa-folder-open" tone="brand" />
            <x-admin-components::stat-card label="Dipublikasikan" :value="$downloads->where('is_active', true)->where('file_available', true)->count()" icon="fa-solid fa-eye" tone="green" />
        </div>

        <form action="{{ route('admin.downloads.index') }}" method="GET" class="app-card app-card-pad flex flex-wrap items-end gap-3">
            <div style="flex:1 1 240px;min-width:0">
                <label for="admin-download-major" class="field-label">Filter Jurusan (Laporan PKL)</label>
                <select name="jurusan" id="admin-download-major" class="app-select">
                    <option value="">Semua Jurusan / Semua Berkas</option>
                    <option value="umum" @selected($majorFilter === 'umum')>Umum / Semua Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" @selected($majorFilter === (string) $major->id)>{{ $major->name }}</option>
                    @endforeach
                </select>
                <p class="field-hint">Memilih jurusan akan menampilkan laporan PKL saja. Pilih Semua Berkas untuk kembali melihat logo dan seluruh laporan.</p>
            </div>
            <button type="submit" class="app-btn app-btn-soft-brand"><i class="fa-solid fa-filter"></i> Filter Jurusan</button>
            @if($majorFilter !== null)<a href="{{ route('admin.downloads.index') }}" class="app-btn">Reset</a>@endif
        </form>

        <x-admin-components::table :head="['Berkas', 'File Asli', 'Status', 'Urutan', 'Aksi']" search-hint="Cari nama, jurusan, deskripsi, atau file…"
            :category-options="['' => 'Semua Kategori'] + \App\Models\SchoolDownload::categories()">
            <x-slot:body>
                @forelse ($downloads as $logo)
                    <tr data-row data-cat="{{ $logo->category }}">
                        <td data-label="Berkas">
                            <div class="table-main">
                                @if($logo->is_image && $logo->file_available)
                                    <img src="{{ route('admin.downloads.preview', $logo) }}" alt="{{ $logo->name }}" loading="lazy"
                                        style="width:72px;height:64px;object-fit:contain;padding:6px;border-radius:8px;background:{{ $logo->dark_preview ? '#202b25' : '#edf3e9' }}">
                                @else
                                    <span class="initials-avatar" aria-hidden="true"><i class="fa-solid {{ $logo->is_image ? 'fa-image' : $logo->document_icon }}"></i></span>
                                @endif
                                <div><span class="badge badge-info">{{ $logo->category_label }}</span><div class="cell-main">{{ $logo->name }}</div>
                                    @if($logo->major_label)<div class="cell-sub"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> {{ $logo->major_label }}</div>@endif
                                    <div class="cell-sub">{{ $logo->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="File Asli">
                            <div class="cell-main">{{ $logo->original_name }}</div>
                            <div class="cell-sub">{{ strtoupper($logo->extension) }} · {{ $logo->formatted_size }} @if($logo->dimensions) · {{ $logo->dimensions }} @endif</div>
                            @unless($logo->file_available)<span class="badge badge-archived">File tidak ditemukan — unggah ulang</span>@endunless
                        </td>
                        <td data-label="Status"><span class="badge {{ $logo->is_active ? 'badge-published' : 'badge-archived' }}">{{ $logo->is_active ? 'Ditampilkan' : 'Disembunyikan' }}</span></td>
                        <td data-label="Urutan">{{ $logo->sort_order }}</td>
                        <td data-label="Aksi">
                            <div class="table-actions">
                                @if($logo->file_available)
                                    <a href="{{ route('admin.downloads.download', $logo) }}" class="act-btn view" title="Unduh file asli" aria-label="Unduh {{ $logo->name }}"><i class="fa-solid fa-download"></i></a>
                                @endif
                                <a href="{{ route('admin.downloads.edit', $logo) }}" class="act-btn edit" title="Edit berkas" aria-label="Edit {{ $logo->name }}"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.downloads.destroy', $logo) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn delete" data-confirm-form="#" data-confirm-danger data-confirm-title="Hapus berkas?"
                                        data-confirm-message="Berkas dan file unggahannya akan dihapus dari daftar unduhan."
                                        aria-label="Hapus {{ $logo->name }}" title="Hapus berkas"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-row data-empty><td colspan="5">
                        <x-admin-components::empty-state icon="fa-solid fa-folder-open" title="Belum ada berkas"
                            :description="$majorFilter !== null ? 'Belum ada laporan PKL untuk jurusan yang dipilih. Pilih filter lain atau tambahkan berkas.' : 'Tambahkan logo sekolah, logo perusahaan, atau contoh laporan PKL. Pengunjung menerima file asli tanpa kompresi.'"
                            action-label="Tambah Berkas" :action-url="route('admin.downloads.create')" />
                    </td></tr>
                @endforelse
            </x-slot:body>
        </x-admin-components::table>
    </div>
@endsection
