@extends('layouts.admin-app')

@section('title', $program->name)

@section('content')
    <div class="max-w-4xl mx-auto animate-fadein">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-[20px] font-extrabold" style="color:var(--text)">{{ $program->name }}</h2>
                @include('admin.components.status-badge', ['status' => $program->status])
            </div>
            <a href="{{ route('admin.programs.index') }}" class="app-btn">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="app-card overflow-hidden">
            @if($program->image)
                <div class="relative">
                    <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->name }}"
                        class="w-full object-cover" style="max-height:340px">
                </div>
            @endif

            <div class="app-card-pad">
                <div class="mb-6">
                    <span class="text-[11px] font-bold uppercase tracking-wider" style="color:var(--brand)">Program Sekolah</span>
                    <h1 class="text-[22px] font-extrabold mt-1" style="color:var(--text)">{{ $program->name }}</h1>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            <span class="badge @class(['badge-published' => $program->status === 'published', 'badge-draft' => $program->status === 'draft', 'badge-archived' => $program->status === 'archived'])">
                                @if($program->status === 'published') Published @elseif($program->status === 'draft') Draft @else Archived @endif
                            </span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Penerbit</span>
                        <span class="detail-value"><i class="fa-regular fa-user mr-1.5"></i>{{ $program->publisher }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dibuat</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($program->created_at)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Diperbarui</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($program->updated_at)->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-[13px] font-bold uppercase tracking-wide mb-3" style="color:var(--text-3)">Deskripsi</h3>
                    <div style="color:var(--text-2);line-height:1.75;font-size:14px" class="prose-legacy">
                        {!! nl2br(e($program->description)) !!}
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 mt-8 pt-6" style="border-top:1px solid var(--border)">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="app-btn app-btn-primary">
                            <i class="fa-solid fa-pen"></i> Edit Program
                        </a>
                        <a href="{{ url('/programs', $program->id) }}" target="_blank" class="app-btn">
                            <i class="fa-solid fa-up-right-from-square"></i> Lihat di Website
                        </a>
                    </div>
                    <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST"
                        data-confirm-form data-confirm-title="Hapus Program"
                        data-confirm-message="Hapus program ini? Tindakan tidak dapat dibatalkan."
                        data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="app-btn app-btn-danger">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection