@extends('layouts.admin-app')

@section('title', $leader->name)

@section('content')
    <div class="fade-up mx-auto max-w-3xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-user-tie"
            kicker="Pemimpin Sekolah"
            :title="Str::limit($leader->name, 60)"
            :subtitle="$leader->position">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.leaders.edit', $leader->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <form action="{{ route('admin.leaders.destroy', $leader->id) }}" method="POST" id="delete-leader-form" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="app-btn app-btn-lg app-btn-danger" onclick="AppConfirm({ title:'Hapus Data', message:'Hapus data {{ addslashes($leader->name) }}? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ document.getElementById('delete-leader-form').submit(); } })"><i class="fa-solid fa-trash"></i></button>
                </form>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card p-6 flex flex-col sm:flex-row items-center gap-5">
            @php
                $imgUrl = $leader->image
                    ? (str_starts_with($leader->image, 'uploads/') ? asset('storage/' . $leader->image) : asset($leader->image))
                    : null;
            @endphp
            @if ($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $leader->name }}" class="rounded-full" style="width:96px;height:96px;object-fit:cover">
            @else
                <div class="initials-avatar" style="width:96px;height:96px;border-radius:20px;font-size:28px">{{ collect(explode(' ', $leader->name))->map(fn ($w) => strtoupper(Str::substr($w, 0, 1)))->take(2)->implode('') }}</div>
            @endif
            <div class="flex-1 text-center sm:text-left">
                <div class="cell-main" style="font-size:19px">{{ $leader->name }}</div>
                <div class="cell-sub">{{ $leader->position }}</div>
                <div class="mt-2 flex flex-wrap gap-2 justify-center sm:justify-start">
                    <span class="badge badge-info">{{ $leader->school }}</span>
                    <span class="badge {{ $leader->is_active ? 'badge-published' : 'badge-archived' }}">{{ $leader->is_active ? 'Aktif' : 'Tersembunyi' }}</span>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h4 class="form-section-title"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Detail</h4>
            <dl class="info-grid">
                <div><dt>Nama Lengkap</dt><dd>{{ $leader->name }}</dd></div>
                <div><dt>Jabatan</dt><dd>{{ $leader->position }}</dd></div>
                <div><dt>Sekolah</dt><dd>{{ $leader->school }}</dd></div>
                <div><dt>Urutan</dt><dd>{{ $leader->order_column ?? '-' }}</dd></div>
                <div style="grid-column:1/-1"><dt>Kutipan</dt><dd>{{ $leader->quote ?? '-' }}</dd></div>
                @if ($leader->facebook_url || $leader->instagram_url || $leader->linkedin_url)
                    <div style="grid-column:1/-1">
                        <dt>Media Sosial</dt>
                        <dd>
                            <div class="flex flex-wrap gap-2">
                                @if ($leader->facebook_url) <a class="badge badge-info" href="{{ $leader->facebook_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i> Facebook</a> @endif
                                @if ($leader->instagram_url) <a class="badge badge-info" href="{{ $leader->instagram_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i> Instagram</a> @endif
                                @if ($leader->linkedin_url) <a class="badge badge-info" href="{{ $leader->linkedin_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin-in"></i> LinkedIn</a> @endif
                            </div>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.leaders.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection