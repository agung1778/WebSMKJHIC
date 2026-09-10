@extends('layouts.admin-app')

@section('title', 'Edit Guru & Staf')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-chalkboard-user"
            kicker="Akademik"
            title="Edit Guru & Staf"
            :subtitle="'Perbarui data: ' . Str::limit($teacher->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.teachers.show', $teacher->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.teacher._form', ['teacher' => $teacher])
        </form>
    </div>
@endsection