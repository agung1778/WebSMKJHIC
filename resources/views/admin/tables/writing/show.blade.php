@extends('layouts.admin-app')

@section('title', $writing->title)

@section('content')
    <div class="fade-up mx-auto max-w-3xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Tulisan"
            :title="Str::limit($writing->title, 60)"
            :subtitle="'oleh ' . $writing->publisher . ' · ' . \Carbon\Carbon::parse($writing->release_date)->translatedFormat('d M Y')">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.writings.edit', $writing->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Tulisan', message:'Hapus tulisan <b>{{ addslashes($writing->title) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.writings.destroy', $writing->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card p-6 md:p-8">
            <h1 class="text-2xl md:text-[28px] font-extrabold leading-tight mb-4" style="color:var(--text)">{{ $writing->title }}</h1>
            <div class="flex items-center gap-2 text-[12.5px] pb-5 mb-6" style="color:var(--text-3);border-bottom:1px solid var(--border)">
                <i class="fa-regular fa-user"></i><span>{{ $writing->publisher }}</span>
                <span class="status-dot" style="background:var(--text-3)"></span>
                <i class="fa-regular fa-calendar"></i><span>{{ \Carbon\Carbon::parse($writing->release_date)->translatedFormat('l, d F Y') }}</span>
            </div>
            <x-admin-components::rich-text :content="$writing->content" />
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.writings.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection