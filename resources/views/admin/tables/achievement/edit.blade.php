@extends('layouts.admin-app')

@section('title', 'Edit Prestasi')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-trophy"
            kicker="Akademik"
            title="Edit Prestasi"
            :subtitle="'Perbarui prestasi: ' . Str::limit($achievement->title, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.achievements.show', $achievement->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.achievement._form', ['achievement' => $achievement])
        </form>
    </div>
@endsection