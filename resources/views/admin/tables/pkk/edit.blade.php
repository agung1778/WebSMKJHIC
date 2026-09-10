@extends('layouts.admin-app')

@section('title', 'Edit Proyek P5/PKK')

@section('content')
    <div class="fade-up mx-auto space-y-6 max-w-5xl">
        <x-admin-components::page-header
            icon="fa-solid fa-lightbulb"
            kicker="Konten"
            title="Edit Proyek P5/PKK"
            :subtitle="'Perbarui proyek: ' . Str::limit($pkk->title, 60)">

            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.pkk.show', $pkk->id) }}"><i class="fa-regular fa-eye"></i> Lihat</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        <form action="{{ route('admin.pkk.update', $pkk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tables.pkk._form', ['pkk' => $pkk])
        </form>
    </div>
@endsection