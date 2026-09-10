@extends('layouts.admin-app')

@section('title', $partner->name)

@section('content')
    <div class="fade-up mx-auto max-w-4xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-handshake"
            kicker="Mitra Industri"
            :title="Str::limit($partner->name, 60)"
            :subtitle="($partner->sector ?? '') . ($partner->city ? ' · ' . $partner->city : '')">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.partners.edit', $partner->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <button class="app-btn app-btn-lg app-btn-danger" type="button" x-data="{}" @click="AppConfirm({ title:'Hapus Mitra', message:'Hapus mitra <b>{{ addslashes($partner->name) }}</b>? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ const f=document.createElement('form'); f.method='POST'; f.action='{{ route('admin.partners.destroy', $partner->id) }}'; f.innerHTML='<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">'; document.body.appendChild(f); f.submit(); } })"><i class="fa-solid fa-trash"></i></button>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="form-section space-y-4 md:col-span-2">
                <div class="flex items-center gap-4">
                    @if($partner->logo)
                        <div class="img-thumb" style="width:88px;height:88px;border-radius:16px;flex-shrink:0">
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" style="height:88px">
                        </div>
                    @endif
                    <div>
                        <div class="cell-main" style="font-size:18px">{{ $partner->name }}</div>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @if($partner->sector)<span class="badge badge-info">{{ $partner->sector }}</span>@endif
                            @if($partner->city)<span class="badge badge-published"><i class="fa-solid fa-location-dot mr-1"></i>{{ $partner->city }}</span>@endif
                        </div>
                    </div>
                </div>

                <h4 class="form-section-title mt-4"><i class="fa-solid fa-file-lines" style="color:var(--brand)"></i> Deskripsi</h4>
                <x-admin-components::rich-text :content="$partner->description" />
            </div>

            <div class="form-section h-fit">
                <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail Kemitraan</h4>
                <dl class="info-grid">
                    <div><dt>Kerja Sama</dt><dd>{{ \Carbon\Carbon::parse($partner->partnership_date)->translatedFormat('d M Y') }}</dd></div>
                    @if($partner->company_contact)
                        <div><dt>Kontak</dt><dd>{{ $partner->company_contact }}</dd></div>
                    @endif
                    <div><dt>Penerbit</dt><dd>{{ $partner->publisher ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.partners.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection