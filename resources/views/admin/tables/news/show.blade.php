@extends('layouts.admin-app')

@section('title', $newsItem->title)

@section('content')
    <div class="fade-up mx-auto max-w-4xl space-y-6">
        @php
            $parsed = \Carbon\Carbon::parse($newsItem->date_published);
        @endphp

        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Berita"
            :title="Str::limit($newsItem->title, 60)"
            subtitle="Diterbitkan {{ $parsed->translatedFormat('l, d F Y') }} · oleh {{ $newsItem->publisher }}">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.news.edit', $newsItem->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Berita', message:'Hapus berita <b>{{ addslashes($newsItem->title) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.news.destroy', $newsItem->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card overflow-hidden">
            @if($newsItem->image)
                <div class="relative">
                    <img src="{{ asset('storage/' . $newsItem->image) }}" alt="{{ $newsItem->title }}" class="w-full" style="max-height:420px;object-fit:cover">
                    <div class="absolute top-4 left-4">
                        <span class="badge badge-published px-3 py-1.5 text-[12px]"><i class="fa-regular fa-calendar mr-1"></i>{{ $parsed->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            @endif
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="initials-avatar">{{ collect(explode(' ', $newsItem->publisher))->map(fn ($w) => strtoupper(Str::substr($w, 0, 1)))->take(2)->implode('') }}</div>
                    <div>
                        <div class="cell-main" style="font-size:14px">{{ $newsItem->publisher }}</div>
                        <div class="cell-sub">{{ $parsed->diffForHumans() }} · {{ $parsed->translatedFormat('H:i') }}</div>
                    </div>
                </div>
                <h1 class="text-2xl md:text-[30px] font-extrabold leading-tight mb-5" style="color:var(--text)">{{ $newsItem->title }}</h1>
                <article class="rich-content">
                    <x-admin-components::rich-text :content="$newsItem->description" />
                </article>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.news.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar</a>
            <a class="app-btn app-btn-primary" href="{{ route('admin.news.edit', $newsItem->id) }}"><i class="fa-solid fa-pen"></i> Edit Berita</a>
            @if($newsItem->image)
                <a class="app-btn" href="{{ asset('storage/' . $newsItem->image) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Gambar</a>
            @endif
        </div>
    </div>
@endsection