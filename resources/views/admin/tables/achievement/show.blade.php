@extends('layouts.admin-app')

@section('title', $achievement->title)

@section('content')
    <div class="fade-up mx-auto max-w-4xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-trophy"
            kicker="Prestasi"
            :title="Str::limit($achievement->title, 60)"
            :subtitle="'Pemenang: ' . $achievement->winner">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.achievements.edit', $achievement->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <form action="{{ route('admin.achievements.destroy', $achievement->id) }}" method="POST" id="delete-achievement-form" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="app-btn app-btn-lg app-btn-danger" onclick="AppConfirm({ title:'Hapus Prestasi', message:'Hapus prestasi {{ addslashes($achievement->title) }}? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ document.getElementById('delete-achievement-form').submit(); } })"><i class="fa-solid fa-trash"></i></button>
                </form>
            </x-slot:actions>
        </x-admin-components::page-header>

        @if($achievement->image)
            <div class="app-card overflow-hidden">
                <img src="{{ asset('storage/' . $achievement->image) }}" alt="{{ $achievement->title }}" class="w-full" style="max-height:380px;object-fit:cover">
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section md:col-span-2">
                <h4 class="form-section-title"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$achievement->description" />
            </div>

            <div class="form-section h-fit">
                <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Info</h4>
                <dl class="info-grid">
                    <div><dt>Kategori</dt><dd>
                        <span class="badge" style="background:{{ $achievement->category === 'Individual' ? 'var(--blue-soft,#e8f2ff)' : 'var(--brand-soft)' }};color:{{ $achievement->category === 'Individual' ? 'var(--blue,#1d6fd6)' : 'var(--brand-deep)' }}">{{ $achievement->category }}</span>
                    </dd></div>
                    <div><dt>Pemenang</dt><dd>{{ $achievement->winner }}</dd></div>
                    <div><dt>Tingkat</dt><dd>{{ $achievement->level }}</dd></div>
                    <div><dt>Tanggal</dt><dd>{{ \Carbon\Carbon::parse($achievement->date)->translatedFormat('d M Y') }}</dd></div>
                    <div><dt>Penerbit</dt><dd>{{ $achievement->publisher ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.achievements.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection