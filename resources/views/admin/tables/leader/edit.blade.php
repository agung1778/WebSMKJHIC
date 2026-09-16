@extends('layouts.admin-app')

@section('title', 'Edit Pemimpin Sekolah')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-4xl">
        <x-admin-components::page-header
            icon="fa-solid fa-user-tie"
            kicker="Akademik"
            title="Edit Pemimpin Sekolah"
            :subtitle="'Perbarui data: ' . Str::limit($leader->name, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.leaders.show', $leader->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.leaders.update', $leader->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.leader._form', ['leader' => $leader])
        </form>
    </div>
@endsection