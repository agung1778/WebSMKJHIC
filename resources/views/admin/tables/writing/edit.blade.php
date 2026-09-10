@extends('layouts.admin-app')

@section('title', 'Edit Tulisan')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-pen-nib"
            kicker="Website"
            title="Edit Tulisan"
            :subtitle="'Perbarui tulisan: ' . Str::limit($writing->title, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.writings.show', $writing->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.writings.update', $writing->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.tables.writing._form', ['writing' => $writing])
        </form>
    </div>
@endsection