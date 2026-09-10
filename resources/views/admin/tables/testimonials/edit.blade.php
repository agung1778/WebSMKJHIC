@extends('layouts.admin-app')

@section('title', 'Edit Testimoni')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Konten"
            title="Edit Testimoni"
            :subtitle="'Perbarui testimoni: ' . Str::limit($testimonial->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.testimonials.show', $testimonial->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.testimonials._form', ['testimonial' => $testimonial])
        </form>
    </div>
@endsection