@extends('layouts.admin-app')

@section('title', 'Edit Jurusan')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-graduation-cap"
            kicker="Akademik"
            title="Edit Jurusan"
            :subtitle="'Perbarui jurusan: ' . Str::limit($major->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.majors.show', $major->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.majors.update', $major->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.major._form', ['major' => $major])
        </form>
    </div>
@endsection