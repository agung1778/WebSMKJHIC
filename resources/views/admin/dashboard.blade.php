@extends('layouts.admin-app')

@section('title', 'Dashboard')

@section('content')
    <div class="animate-fadein">
        {{-- Greeting --}}
        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-[22px] font-extrabold text-[#222]" style="color:var(--text);font-weight:800">Halo, {{ Auth::user()->name }} 👋</h2>
                <p class="text-[13px] mt-1" style="color:var(--text-3)">Pantau dan kelola konten website SMK Amaliah 1 &amp; 2 di satu tempat.</p>
            </div>
            <div class="flex items-center gap-3">
                @if ($spmb)
                    <span class="badge @if ($spmb->status === 'Buka') badge-published @else badge-archived @endif">
                        <i class="fa-solid fa-door-open"></i>
                        Info SPMB: {{ $spmb->status }}
                    </span>
                @endif
                <span class="badge badge-info hide-mob"><i class="fa-regular fa-calendar"></i><span id="current-date"></span></span>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
            @php
                $cards = [
                    ['label' => 'Program Pendidikan', 'value' => $stats['programs'], 'icon' => 'fa-solid fa-graduation-cap', 'c' => 'violet', 'meta' => $stats['programsPublished'] . ' publik · ' . $stats['programsDraft'] . ' draft', 'route' => route('admin.programs.index')],
                    ['label' => 'Berita', 'value' => $stats['news'], 'icon' => 'fa-solid fa-newspaper', 'c' => 'blue', 'meta' => 'Artikel terbaru sekolah', 'route' => route('admin.news.index')],
                    ['label' => 'Guru & Staf', 'value' => $stats['teachers'], 'icon' => 'fa-solid fa-user-tie', 'c' => 'sky', 'meta' => 'Tenaga pendidik', 'route' => route('admin.teachers.index')],
                    ['label' => 'Jumlah Siswa', 'value' => number_format($stats['students']), 'icon' => 'fa-solid fa-user-graduate', 'c' => 'green', 'meta' => 'Statistik sekolah', 'route' => route('admin.school_settings.edit')],
                    ['label' => 'Prestasi', 'value' => $stats['achievements'], 'icon' => 'fa-solid fa-trophy', 'c' => 'amber', 'meta' => 'Pencapaian siswa', 'route' => route('admin.achievements.index')],
                    ['label' => 'Fasilitas', 'value' => $stats['facilities'], 'icon' => 'fa-solid fa-building', 'c' => 'teal', 'meta' => 'Sarana sekolah', 'route' => route('admin.facilities.index')],
                ];
            @endphp
            @foreach ($cards as $card)
                <a href="{{ $card['route'] }}" class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-value">{{ $card['value'] }}</div>
                            <div class="stat-label">{{ $card['label'] }}</div>
                        </div>
                        <span style="width:40px;height:40px;border-radius:12px;background:var(--{{ $card['c'] }}-soft);color:var(--{{ $card['c'] }});display:flex;align-items:center;justify-content:center;font-size:16px">
                            <i class="{{ $card['icon'] }}"></i>
                        </span>
                    </div>
                    <div class="stat-meta">{{ $card['meta'] }}</div>
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kiri (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Quick actions --}}
                <div class="app-card app-card-pad">
                    <div class="card-title"><i class="fa-solid fa-bolt" style="color:var(--brand)"></i>Aksi Cepat</div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4">
                        @php
                            $quick = [
                                ['Tambah Berita', 'fa-solid fa-newspaper', route('admin.news.create'), 'blue'],
                                ['Tambah Program', 'fa-solid fa-graduation-cap', route('admin.programs.create'), 'violet'],
                                ['Tambah Guru', 'fa-solid fa-user-tie', route('admin.teachers.create'), 'sky'],
                                ['Tambah Prestasi', 'fa-solid fa-trophy', route('admin.achievements.create'), 'amber'],
                                ['Tambah Fasilitas', 'fa-solid fa-building', route('admin.facilities.create'), 'teal'],
                                ['Atur Info SPMB', 'fa-solid fa-file-circle-check', route('admin.spmb_settings.edit'), 'green'],
                            ];
                        @endphp
                        @foreach ($quick as $q)
                            <a href="{{ $q[2] }}" class="flex items-center gap-3 p-3 rounded-xl" style="border:1px solid var(--border);background:var(--surface-2);transition:all .18s ease" onmouseover="this.style.borderColor='var(--brand-strong)';this.style.transform='translateY(-1px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                                <span style="width:34px;height:34px;border-radius:10px;background:var(--{{ $q[3] }}-soft);color:var(--{{ $q[3] }});display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0"><i class="{{ $q[1] }}"></i></span>
                                <span class="text-[12.5px] font-semibold" style="color:var(--text)">{{ $q[0] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Berita terbaru --}}
                <div class="app-card">
                    <div class="flex items-center justify-between px-5 pt-5">
                        <div class="card-title"><i class="fa-solid fa-newspaper" style="color:var(--blue)"></i>Berita Terbaru</div>
                        <a href="{{ route('admin.news.index') }}" class="text-[12.5px] font-semibold" style="color:var(--brand)">Lihat semua <i class="fa-solid fa-arrow-right ml-1" style="font-size:10px"></i></a>
                    </div>
                    <div class="mt-3">
                        @forelse ($latestNews as $n)
                            <a href="{{ route('admin.news.edit', $n->id) }}" class="flex items-center gap-4 px-5 py-3 transition" style="border-top:1px solid var(--border)" onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                                @if ($n->image)
                                    <img src="{{ asset('storage/' . $n->image) }}" alt="" loading="lazy" class="w-12 h-12 rounded-xl object-cover" style="border:1px solid var(--border)">
                                @else
                                    <span style="width:48px;height:48px;border-radius:12px;background:var(--blue-soft);color:var(--blue);display:flex;align-items:center;justify-content:center"><i class="fa-regular fa-file-lines"></i></span>
                                @endif
                                <div style="min-width:0;flex:1">
                                    <div class="text-[13.5px] font-semibold truncate" style="color:var(--text)">{{ $n->title }}</div>
                                    <div class="text-[11.5px]" style="color:var(--text-3)">{{ \Carbon\Carbon::parse($n->date_published)->locale('id')->translatedFormat('d M Y') }} · oleh {{ $n->publisher }}</div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[11px]" style="color:var(--text-3)"></i>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center text-[13px]" style="color:var(--text-3);border-top:1px solid var(--border)">Belum ada berita. <a href="{{ route('admin.news.create') }}" style="color:var(--brand);font-weight:600">Tulis yang pertama →</a></div>
                        @endforelse
                    </div>
                </div>

                {{-- Program terbaru --}}
                <div class="app-card">
                    <div class="flex items-center justify-between px-5 pt-5">
                        <div class="card-title"><i class="fa-solid fa-graduation-cap" style="color:var(--violet)"></i>Program Pendidikan Terbaru</div>
                        <a href="{{ route('admin.programs.index') }}" class="text-[12.5px] font-semibold" style="color:var(--brand)">Lihat semua <i class="fa-solid fa-arrow-right ml-1" style="font-size:10px"></i></a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 p-5">
                        @forelse ($latestPrograms as $p)
                            <a href="{{ route('admin.programs.edit', $p->id) }}" class="app-card overflow-hidden transition" onmouseover="this.style.boxShadow='var(--shadow)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform='none'">
                                <div style="aspect-ratio:16/8;overflow:hidden;background:var(--surface-3)">
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="" loading="lazy" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[13px] font-bold truncate" style="color:var(--text)">{{ $p->name }}</span>
                                        @include('admin.components.status-badge', ['status' => $p->status])
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-8 text-center text-[13px]" style="color:var(--text-3)">Belum ada program.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Kanan (1/3) --}}
            <div class="space-y-6">
                {{-- Content status --}}
                <div class="app-card app-card-pad">
                    <div class="card-title"><i class="fa-solid fa-diagram-project" style="color:var(--teal)"></i>Status Konten</div>
                    <div class="mini-stats mt-4">
                        @php
                            $statuses = [
                                ['Jurusan', $stats['majors'], 'fa-solid fa-layer-group', 'violet'],
                                ['Ekstrakurikuler', $stats['extracurriculars'], 'fa-solid fa-futbol', 'fuchsia'],
                                ['Hero Images', $stats['images'], 'fa-solid fa-image', 'sky'],
                                ['Tulisan', $stats['writings'], 'fa-solid fa-book-open', 'green'],
                                ['Admin Terdaftar', $stats['users'], 'fa-solid fa-users', 'amber'],
                                ['Visitor Hari Ini', $stats['visitorsToday'], 'fa-solid fa-eye', 'indigo'],
                            ];
                        @endphp
                        @foreach ($statuses as $s)
                            <div class="mini-stat">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $s[2] }}" style="color:var(--{{ $s[3] }})"></i>
                                    <span class="ms-label">{{ $s[0] }}</span>
                                </div>
                                <div class="ms-value">{{ $s[1] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4" style="border-top:1px solid var(--border)">
                        <a href="{{ route('admin.traffic.index') }}" class="flex items-center justify-between text-[13px] font-semibold" style="color:var(--text-2)">
                            <span><i class="fa-solid fa-chart-line mr-2" style="color:var(--brand)"></i>Buka Traffic Website</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>

                {{-- Aktivitas admin terbaru --}}
                <div class="app-card">
                    <div class="card-title px-5 pt-5"><i class="fa-solid fa-user-clock" style="color:var(--amber)"></i>Aktivitas Admin</div>
                    <div class="mt-3">
                        @forelse ($recentUsers as $u)
                            <div class="flex items-center gap-3 px-5 py-3" style="border-top:1px solid var(--border)">
                                <span style="width:34px;height:34px;border-radius:10px;background:var(--green-soft);color:var(--green);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700">{{ strtoupper(collect(explode(' ', trim($u->name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode('')) }}</span>
                                <div style="min-width:0">
                                    <div class="text-[12.5px] font-semibold truncate" style="color:var(--text)">{{ $u->name }}</div>
                                    <div class="text-[11.5px]" style="color:var(--text-3)">Bergabung {{ \Carbon\Carbon::parse($u->created_at)->locale('id')->diffForHumans() }} · {{ $u->role }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-center text-[13px]" style="color:var(--text-3);border-top:1px solid var(--border)">Belum ada admin lain.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('current-date');
            if (!el) return;
            var fmt = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            el.textContent = fmt.format(new Date());
        });
    </script>
@endpush