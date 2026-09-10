@extends('layouts.admin-app')

@section('title', 'Edit Berita')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-newspaper"
            kicker="Konten"
            title="Edit Berita"
            :subtitle="'Perbarui berita: ' . Str::limit($newsItem->title, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.news.show', $newsItem->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.news.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.news._form', ['newsItem' => $newsItem])
        </form>
    </div>
@endsection