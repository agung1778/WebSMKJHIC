@extends('layouts.admin-app')

@section('title', 'Edit Ekstrakurikuler')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-futbol"
            kicker="Akademik"
            title="Edit Ekstrakurikuler"
            :subtitle="'Perbarui ekskul: ' . Str::limit($extracurricular->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.extracurriculars.show', $extracurricular->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.extracurriculars.update', $extracurricular->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.extracurricular._form', ['extracurricular' => $extracurricular])
        </form>
    </div>
@endsection