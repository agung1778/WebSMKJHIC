@php
    $teacherInitial = !empty(trim($teacher->name)) ? strtoupper(substr(trim($teacher->name), 0, 1)) : '?';
    $teacherSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400">' .
        '<rect width="400" height="400" fill="#eef1f5"/>' .
        '<text x="50%" y="54%" font-family="Poppins, Arial, sans-serif" font-size="170" font-weight="700" fill="#aab4c3" text-anchor="middle" dominant-baseline="middle">' .
        $teacherInitial . '</text></svg>';
    $teacherPlaceholder = 'data:image/svg+xml;base64,' . base64_encode($teacherSvg);
@endphp

<a href="{{ route('public.teachers.show', $teacher->id) }}" class="teacher-card group" aria-label="{{ __('Lihat detail') }} {{ $teacher->name }}">

    {{-- Foto guru 1:1 --}}
    <div class="teacher-card__photo">
        <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : $teacherPlaceholder }}"
            alt="{{ __('Foto') }} {{ $teacher->name }}" loading="lazy"
            onerror="this.onerror=null;this.src='{{ $teacherPlaceholder }}';">
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
