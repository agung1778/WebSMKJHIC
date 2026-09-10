@extends('layouts.admin-app')

@section('title', 'Tambah Testimoni')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-comment-dots"
            kicker="Konten"
            title="Tambah Testimoni"
            subtitle="Tambahkan testimoni alumni untuk website sekolah." />

        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.testimonials._form', ['testimonial' => null])
        </form>
    </div>
@endsection