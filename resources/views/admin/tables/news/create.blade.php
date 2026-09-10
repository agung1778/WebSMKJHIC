@extends('layouts.admin-app')

@section('title', 'Tulis Berita')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Konten"
            title="Tulis Berita"
            subtitle="Buat berita baru yang akan tampil di website sekolah." />

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.tables.news._form', ['newsItem' => null])
        </form>
    </div>
@endsection