@extends('layouts.admin-app')

@section('title', 'Edit Mitra')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-handshake"
            kicker="Konten"
            title="Edit Mitra"
            :subtitle="'Perbarui mitra: ' . Str::limit($partner->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.partners.show', $partner->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.partners._form', ['partner' => $partner])
        </form>
    </div>
@endsection