@extends('layouts.admin-app')

@section('title', $testimonial->name)

@section('content')
    <div class="fade-up mx-auto max-w-3xl space-y-6">
        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Testimoni"
            :title="Str::limit($testimonial->name, 60)"
            :subtitle="'Alumni ' . $testimonial->alumni_year . ($testimonial->major ? ' · ' . $testimonial->major->name : '')">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.testimonials.edit', $testimonial->id) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" id="delete-testimonial-form" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="app-btn app-btn-lg app-btn-danger" onclick="AppConfirm({ title:'Hapus Testimoni', message:'Hapus testimoni {{ addslashes($testimonial->name) }}? Tindakan ini tidak dapat dibatalkan.', danger:true, confirmText:'Ya, hapus', onConfirm(){ document.getElementById('delete-testimonial-form').submit(); } })"><i class="fa-solid fa-trash"></i></button>
                </form>
            </x-slot:actions>
        </x-admin-components::page-header>

        <div class="app-card p-6 md:p-8">
            <div class="flex items-center gap-4 mb-6">
                @if($testimonial->photo)
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}" class="rounded-full" style="width:64px;height:64px;object-fit:cover">
                @else
                    <div class="initials-avatar" style="width:64px;height:64px;border-radius:50%;font-size:20px">{{ collect(explode(' ', $testimonial->name))->map(fn ($w) => strtoupper(Str::substr($w, 0, 1)))->take(2)->implode('') }}</div>
                @endif
                <div>
                    <div class="cell-main" style="font-size:16px">{{ $testimonial->name }}</div>
                    <div class="cell-sub">Angkatan {{ $testimonial->alumni_year }} · {{ $testimonial->major->name ?? '-' }}</div>
                </div>
                <span class="ml-auto text-[var(--amber)]"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
            </div>
            <blockquote class="rich-content" style="border-left:3px solid var(--brand);background:var(--brand-soft);padding:16px 20px;border-radius:0 16px 16px 0;font-style:italic">
                <x-admin-components::rich-text :content="$testimonial->description" />
            </blockquote>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a class="app-btn" href="{{ route('admin.testimonials.index') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
@endsection