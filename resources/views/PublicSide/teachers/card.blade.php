@php
    $teacherInitial = !empty(trim($teacher->name)) ? strtoupper(substr(trim($teacher->name), 0, 1)) : '?';
@endphp

<a href="{{ route('public.teachers.show', $teacher->id) }}" class="teacher-card group" aria-label="{{ __('Lihat detail') }} {{ $teacher->name }}">

    @php
        $isStaff = ($teacher->school ?? '') === 'Staff';
        $chipLabel = $teacher->school ?: 'Staff';
    @endphp

    {{-- Foto guru 1:1 --}}
    <div class="teacher-card__photo">
        <span class="ts-chip {{ !$isStaff ? 'ts-chip--green' : '' }}">
            <i class="fas {{ $isStaff ? 'fa-users-gear' : 'fa-school' }}"></i>
            {{ $chipLabel }}
        </span>

        {{-- Avatar inisial sebagai lapisan bawah (tampil saat foto kosong/gagal dimuat) --}}
        <div class="teacher-card__avatar">{{ $teacherInitial }}</div>

        @if ($teacher->photo)
            <img src="{{ asset('storage/' . $teacher->photo) }}"
                alt="{{ __('Foto') }} {{ $teacher->name }}" loading="lazy"
                class="teacher-card__img" onerror="this.remove()">
        @endif
    </div>

    {{-- Konten kartu --}}
    <div class="p-5 flex flex-col flex-1">
        <span class="inline-flex items-start text-xs font-semibold text-[#63cd00] mb-2 uppercase tracking-wider">
            {{ $teacher->position }}
        </span>

        <h3 class="text-base font-bold text-gray-900 mb-1.5 leading-tight line-clamp-2">
            {{ $teacher->name }}
        </h3>

        <p class="text-gray-500 text-sm flex-grow line-clamp-2 leading-relaxed">
            {{ $teacher->subject ?: $teacher->position }}
        </p>

        {{-- Footer kartu: selalu tampil agar bagian bawah semua card sejajar --}}
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 min-w-0">
                <i class="fas fa-school text-gray-400 text-xs flex-shrink-0"></i>
                <span class="truncate">{{ $teacher->school ?: '—' }}</span>
            </span>
            <span class="teacher-card__hint flex-shrink-0">
                {{ __('Detail') }}
                <i class="fas fa-arrow-right text-xs"></i>
            </span>
        </div>
    </div>
</a>