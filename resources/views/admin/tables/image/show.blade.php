@extends('layouts.admin-app')

@section('title', $image->title ?? $image->filename)

@section('content')
    <div class="fade-up mx-auto max-w-3xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-images"
            kicker="Hero & Media"
            :title="Str::limit($image->title ?? $image->filename, 60)"
            :subtitle="'storage/' . $image->path">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.image.edit', $image->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <form action="{{ route('admin.image.destroy', $image->id) }}" method="POST" id="delete-image-form" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="app-btn app-btn-lg app-btn-danger" onclick="AppConfirm({ title:'Hapus Gambar', message:'Hapus gambar {{ addslashes($image->title ?? $image->filename) }}? File akan dihapus permanen.', danger:true, confirmText:'Ya, hapus', onConfirm(){ document.getElementById('delete-image-form').submit(); } })"><i class="fa-solid fa-trash"></i></button>
                </form>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card overflow-hidden">
            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->title ?? $image->filename }}" class="w-full" style="max-height:480px;object-fit:contain;background:var(--app-bg)">
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail File</h4>
            <dl class="info-grid">
                <div><dt>Judul</dt><dd>{{ $image->title ?? '—' }}</dd></div>
                <div><dt>Nama File</dt><dd>{{ $image->filename }}</dd></div>
                <div><dt>Tipe</dt><dd><code>{{ $image->mime_type ?? '-' }}</code></dd></div>
                <div><dt>Ukuran</dt><dd>{{ $image->size ? number_format($image->size / 1024 / 1024, 2) . ' MB' : '-' }}</dd></div>
                <div><dt>Deskripsi</dt><dd>{{ $image->description ?? '—' }}</dd></div>
                <div><dt>Diunggah</dt><dd>{{ $image->created_at->translatedFormat('d M Y, H:i') }}</dd></div>
            </dl>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.image.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <a class="app-btn app-btn-primary" href="{{ asset('storage/' . $image->path) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Gambar</a>
        </div>
    </div>
@endsection