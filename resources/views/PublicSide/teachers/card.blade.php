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

        @if ($teacher->photo && !str_contains($teacher->photo, 'default-user'))
            <img src="{{ img_url($teacher->photo, 400, 400) }}" width="400" height="400"
                alt="{{ __('Foto') }} {{ $teacher->name }}" loading="lazy"
                class="teacher-card__img" onerror="this.remove()">
        @endif
    </div>

    {{-- Konten kartu --}}
    <div class="p-4 sm:p-5 flex flex-col flex-1">
        <span class="inline-flex items-start text-xs font-bold text-[#2e7000] mb-2 uppercase tracking-wider min-h-[2.4em] leading-snug">
            {{ $teacher->position }}
        </span>

        <h3 class="text-base font-bold text-[#1c1c1d] mb-1.5 leading-tight line-clamp-2 min-h-[2.7em] flex items-center">
            {{ $teacher->name }}
        </h3>

        <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed min-h-[2.6em] mb-2">
            @if(!empty($teacher->subject))
                {{ $teacher->subject }}
            @elseif(!empty($teacher->additional_duties))
                {{ $teacher->additional_duties }}
            @else
                {{ $teacher->position }}
            @endif
        </p>

        @if(!empty($teacher->additional_duties) && !empty($teacher->subject))
            <div class="mb-2">
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/70 rounded-md px-2 py-0.5 line-clamp-1" title="{{ $teacher->additional_duties }}">
                    <i class="fas fa-list-check text-[10px] text-[#2e7000] flex-shrink-0"></i>
                    <span class="truncate">{{ $teacher->additional_duties }}</span>
                </span>
            </div>
        @endif

        {{-- Footer kartu: selalu tampil di paling bawah agar semua card sejajar --}}
        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 min-w-0">
                <i class="fas fa-school text-[#2e7000] text-xs flex-shrink-0"></i>
                <span class="truncate">{{ $teacher->school ?: '—' }}</span>
            </span>
            <span class="teacher-card__hint flex-shrink-0">
                {{ __('Detail') }}
                <i class="fas fa-arrow-right text-xs"></i>
            </span>
        </div>
    </div>
</a>