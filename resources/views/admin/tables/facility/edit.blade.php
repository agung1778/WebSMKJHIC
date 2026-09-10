@extends('layouts.admin-app')

@section('title', 'Edit Fasilitas')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-building-columns"
            kicker="Konten"
            title="Edit Fasilitas"
            :subtitle="'Perbarui fasilitas: ' . Str::limit($facility->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.facilities.show', $facility->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.facility._form', ['facility' => $facility])
        </form>
    </div>
@endsection