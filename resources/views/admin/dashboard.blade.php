@extends('layouts.admin-app')

@section('title', 'Dashboard')

@section('content')
@php
    $userName     = Auth::user()->name;
    $userEmail    = Auth::user()->email;
    $userRole     = Auth::user()->role;
    $initials     = strtoupper(collect(explode(' ', trim($userName)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode(''));
    $hasAvatar    = Auth::user()->avatar && \Storage::disk('public')->exists(Auth::user()->avatar);
    $isOnline     = optional(Auth::user()->session)->last_activity && (time() - Auth::user()->session->last_activity) < 300;

    $hour  = (int) now()->format('H');
    $hi    = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 19 ? 'Selamat Sore' : 'Selamat Malam'));
    $today = now()->locale('id')->translatedFormat('l, d F Y');

    $monthVisitors = \App\Models\TrafficVisitor::whereMonth('created_at', now()->month)->count();
    $weekVisitors  = \App\Models\TrafficVisitor::where('created_at', '>=', now()->startOfWeek())->count();
    $totalPV       = \App\Models\TrafficVisitor::count();
    $draftCount    = $stats['programsDraft'] ?? 0;

    $diskFree  = @disk_free_space('/')  ? round(@disk_free_space('/') / 1073741824, 1) : 0;
    $diskTotal = @disk_total_space('/') ? round(@disk_total_space('/') / 1073741824, 1) : 0;
    $diskUsed  = max(0, $diskTotal - $diskFree);
    $diskPct   = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;

    $chart7    = [];
    $chart30   = [];
    $chart12   = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = now()->subDays($i);
        $chart7['labels'][] = $d->locale('id')->isoFormat('dd DD');
        $chart7['data'][]   = \App\Models\TrafficVisitor::whereDate('created_at', $d)->count();
    }
    for ($i = 29; $i >= 0; $i--) {
        $d = now()->subDays($i);
        $chart30['labels'][] = $d->locale('id')->isoFormat('DD MMM');
        $chart30['data'][]   = \App\Models\TrafficVisitor::whereDate('created_at', $d)->count();
    }
    for ($i = 11; $i >= 0; $i--) {
        $m = now()->subMonths($i);
        $chart12['labels'][] = $m->locale('id')->isoFormat('MMM');
        $chart12['data'][]   = \App\Models\TrafficVisitor::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
    }

    $calYear = now()->year;
    $calMonth = now()->month;
    $calFirstDow = \Carbon\Carbon::create($calYear, $calMonth, 1)->dayOfWeek; // 0 = Minggu
    $calDays = now()->daysInMonth;
    $calToday = now()->day;
    $calDow = ['M', 'S', 'S', 'R', 'K', 'J', 'S'];
    $calDowFull = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
@endphp

<div class="space-y-6">

    {{-- ==================== HERO CARD ==================== --}}
    <div class="hero-card fade-up">
        <div class="hero-grid">
            <div class="hero-msg">
                <div class="hero-hi">{{ $today }}</div>
                <div class="hero-title">{{ $hi }}, {{ $userName }} 👋</div>
                <div class="hero-sub">Kelola seluruh informasi website SMK Amaliah dari dashboard ini — berita, jurusan, fasilitas, hingga pendaftaran SPMB.</div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="hero-chip">
                    <span class="status-dot"></span>
                    {{ $isOnline ? 'Sesi aktif' : 'Offline' }}
                </span>
                <span class="hero-chip">
                    <i class="fa-solid {{ $userRole === 'superadmin' ? 'fa-crown' : 'fa-shield-halved' }}" style="color:#8AE41F"></i>
                    {{ ucfirst($userRole) }}
                </span>
                <a href="{{ url('/') }}" target="_blank" class="app-btn app-btn-primary">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span class="hide-mob">Lihat Website</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ==================== QUICK ACTIONS ==================== --}}
    <div class="app-card app-card-pad fade-up">
        <div class="flex items-center gap-3 mb-5">
            <span class="sh-icon" style="width:38px;height:38px;border-radius:12px;background:var(--brand-soft);color:var(--brand-deep);display:flex;align-items:center;justify-content:center">
                <i class="fa-solid fa-bolt"></i>
            </span>
            <div>
                <div class="font-bold text-[#1C1C1D]" style="font-size:14.5px">Aksi Cepat</div>
                <div class="text-xs text-gray-400">Tambahkan konten baru dalam sekali klik</div>
            </div>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @php
                $quickActions = [
                    ['label' => 'Berita',   'icon' => 'fa-solid fa-newspaper',      'url' => route('admin.news.create'),       'bg' => '#EFF6FF', 'fg' => '#2563EB'],
                    ['label' => 'Jurusan',  'icon' => 'fa-solid fa-layer-group',    'url' => route('admin.majors.create'),     'bg' => '#F5F3FF', 'fg' => '#7C3AED'],
                    ['label' => 'Program',  'icon' => 'fa-solid fa-graduation-cap', 'url' => route('admin.programs.create'),   'bg' => '#FFF7ED', 'fg' => '#EA580C'],
                    ['label' => 'Guru',     'icon' => 'fa-solid fa-user-tie',       'url' => route('admin.teachers.create'),   'bg' => '#F0F9FF', 'fg' => '#0284C7'],
                    ['label' => 'Prestasi', 'icon' => 'fa-solid fa-trophy',         'url' => route('admin.achievements.create'), 'bg' => '#FFFBEB', 'fg' => '#D97706'],
                    ['label' => 'Fasilitas','icon' => 'fa-solid fa-building',       'url' => route('admin.facilities.create'), 'bg' => '#F0FDFA', 'fg' => '#0D9488'],
                    ['label' => 'Hero',     'icon' => 'fa-solid fa-images',         'url' => route('admin.image.index'),       'bg' => '#FDF4FF', 'fg' => '#C026D3'],
                    ['label' => 'SPMB',     'icon' => 'fa-solid fa-file-circle-check', 'url' => route('admin.spmb_settings.edit'), 'bg' => '#E8FBF0', 'fg' => '#16A34A'],
                ];
            @endphp
            @foreach($quickActions as $qa)
                <a href="{{ $qa['url'] }}" class="qa-btn">
                    <div class="qa-icon" style="background:{{ $qa['bg'] }};color:{{ $qa['fg'] }}">
                        <i class="{{ $qa['icon'] }}"></i>
                    </div>
                    <span>{{ $qa['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ==================== STATISTIK ==================== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total User',        'value' => number_format($stats['users']),          'icon' => 'fa-solid fa-users',     'bg' => '#F5F3FF', 'fg' => '#7C3AED', 'sub' => 'Akun admin & superadmin'],
            ['label' => 'Total Artikel',     'value' => number_format($stats['news']),           'icon' => 'fa-solid fa-newspaper', 'bg' => '#EFF6FF', 'fg' => '#2563EB', 'sub' => 'Berita terpublish'],
            ['label' => 'Galeri Media',      'value' => number_format($stats['images']),         'icon' => 'fa-solid fa-images',    'bg' => '#FDF4FF', 'fg' => '#C026D3', 'sub' => 'Aset gambar website'],
            ['label' => 'Pengunjung Hari Ini','value' => number_format($stats['visitorsToday']),'icon' => 'fa-solid fa-chart-line','bg' => '#E8FBF0', 'fg' => '#16A34A', 'sub' => 'Visitor tercatat'],
            ['label' => 'Visitor Bulan Ini', 'value' => number_format($monthVisitors),           'icon' => 'fa-solid fa-globe',      'bg' => '#FFF7ED', 'fg' => '#EA580C', 'sub' => 'Total bulan ' . now()->locale('id')->isoFormat('MMMM')],
            ['label' => 'Program Pendidikan','value' => number_format($stats['programs']),       'icon' => 'fa-solid fa-graduation-cap', 'bg' => '#F0FDFA', 'fg' => '#0D9488', 'sub' => $stats['programsPublished'] . ' terpublish'],
            ['label' => 'Program Draft',     'value' => number_format($draftCount),              'icon' => 'fa-solid fa-pen-ruler', 'bg' => '#FFFBEB', 'fg' => '#D97706', 'sub' => 'Menunggu diterbitkan'],
            ['label' => 'Penggunaan Storage','value' => round($stats['students'] > 0 ? $stats['students'] / 1000 : 0, 1) . ' GB', 'icon' => 'fa-solid fa-database', 'bg' => '#F8FAFC', 'fg' => '#475569', 'sub' => "Hosting $diskUsed/$diskTotal GB"],
        ] as $st)
            <x-admin-components::stat-card :label="$st['label']" :value="$st['value']" :icon="$st['icon']" :bg="$st['bg']" :fg="$st['fg']" :sub="$st['sub']" />
        @endforeach
    </div>

    {{-- ==================== MAIN: 2/3 + 1/3 ==================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ==== LEFT COLUMN ==== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- GRAFIK PENGUNJUNG --}}
            <div class="app-card app-card-pad fade-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon" style="width:38px;height:38px;border-radius:12px;background:var(--brand-soft);color:var(--brand-deep);display:flex;align-items:center;justify-content:center">
                            <i class="fa-solid fa-chart-area"></i>
                        </span>
                        <div>
                            <div class="font-bold text-[#1C1C1D]" style="font-size:14.5px">Statistik Pengunjung</div>
                            <div class="text-xs text-gray-400">Aktivitas visitor website</div>
                        </div>
                    </div>
                    <div class="range-tabs" x-data="{ range: '7d' }">
                        <button :class="range==='7d' && 'active'" @click="range='7d'; window.__drawChart('7d')">7 Hari</button>
                        <button :class="range==='30d' && 'active'" @click="range='30d'; window.__drawChart('30d')">30 Hari</button>
                        <button :class="range==='12m' && 'active'" @click="range='12m'; window.__drawChart('12m')">12 Bulan</button>
                    </div>
                </div>
                <div style="height:280px;position:relative">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>

            {{-- BERITA TERBARU --}}
            <div class="app-card app-card-hover overflow-hidden fade-up">
                <div class="card-head">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon"><i class="fa-solid fa-newspaper"></i></span>
                        <div>
                            <div class="font-bold text-[#1C1C1D] text-[14.5px]">Artikel Terbaru</div>
                            <div class="text-xs text-gray-400">Berita terakhir yang diterbitkan</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">Lihat semua &rarr;</a>
                </div>
                <div class="p-4 pt-2">
                    @forelse($latestNews as $n)
                        <a href="{{ route('admin.news.edit', $n->id) }}" class="list-row">
                            @if($n->image)
                                <img src="{{ asset('storage/' . $n->image) }}" alt="" loading="lazy" class="w-11 h-11 rounded-xl object-cover flex-shrink-0" style="border:1px solid var(--border)">
                            @else
                                <span class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF;color:#2563EB"><i class="fa-regular fa-file-lines"></i></span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[#1C1C1D] truncate">{{ $n->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($n->date_published)->locale('id')->translatedFormat('d M Y') }} · {{ $n->publisher }}</div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 flex-shrink-0"></i>
                        </a>
                    @empty
                        <div class="py-10 text-center text-sm text-gray-400">Belum ada berita. <a href="{{ route('admin.news.create') }}" class="font-semibold text-brand-deep">Tulis yang pertama &rarr;</a></div>
                    @endforelse
                </div>
            </div>

            {{-- PROGRAM TERBARU --}}
            <div class="app-card app-card-hover overflow-hidden fade-up">
                <div class="card-head">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                        <div>
                            <div class="font-bold text-[#1C1C1D] text-[14.5px]">Program Pendidikan</div>
                            <div class="text-xs text-gray-400">Program terbaru yang dikelola</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.programs.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">Lihat semua &rarr;</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                    @forelse($latestPrograms as $p)
                        <a href="{{ route('admin.programs.edit', $p->id) }}" class="prog-card">
                            <div class="prog-media">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background:var(--surface-3)"><i class="fa-solid fa-graduation-cap text-gray-300 text-xl"></i></div>
                                @endif
                            </div>
                            <div class="prog-body">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-bold text-[#1C1C1D] truncate">{{ $p->name }}</span>
                                    <x-admin-components::status-badge :status="$p->status" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-8 text-center text-sm text-gray-400">Belum ada program.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==== RIGHT COLUMN ==== --}}
        <div class="space-y-6">

            {{-- STATUS WEBSITE --}}
            <div class="app-card app-card-pad fade-up">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon"><i class="fa-solid fa-server"></i></span>
                        <div class="font-bold text-[#1C1C1D] text-[14.5px]">Status Website</div>
                    </div>
                    <span class="badge badge-active"><i class="fa-solid fa-circle" style="font-size:6px"></i> Online</span>
                </div>
                <div class="space-y-2">
                    <div class="info-row">
                        <div class="flex items-center justify-between">
                            <span class="info-label">Info SPMB</span>
                            @if($spmb)
                                <x-admin-components::status-badge :type="$spmb->status === 'Buka' ? 'buka' : 'tutup'" />
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="flex items-center justify-between">
                            <span class="info-label">Hari Ini</span>
                            <span class="text-sm font-bold text-[#1C1C1D]">{{ number_format($stats['visitorsToday']) }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="flex items-center justify-between">
                            <span class="info-label">Minggu Ini</span>
                            <span class="text-sm font-bold text-[#1C1C1D]">{{ number_format($weekVisitors) }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="flex items-center justify-between">
                            <span class="info-label">Total Page Views</span>
                            <span class="text-sm font-bold text-[#1C1C1D]">{{ number_format($totalPV) }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="flex items-center justify-between">
                            <span class="info-label">Konten Aktif</span>
                            <span class="text-sm font-bold text-[#1C1C1D]">{{ number_format(($stats['news'] + $stats['programs'] + $stats['majors'] + $stats['facilities'])) }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.traffic.index') }}" class="app-btn app-btn-soft-brand mt-4" style="width:100%">
                    <i class="fa-solid fa-chart-line"></i> Buka Traffic
                </a>
            </div>

            {{-- KALENDER --}}
            <div class="app-card cal-card app-card-pad fade-up">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon"><i class="fa-solid fa-calendar-days"></i></span>
                        <div>
                            <div class="font-bold text-[#1C1C1D] text-[14.5px]">{{ now()->locale('id')->translatedFormat('F Y') }}</div>
                            <div class="text-xs text-gray-400">Kalender</div>
                        </div>
                    </div>
                </div>
                <div class="cal-grid">
                    @foreach($calDow as $i => $d)
                        @if($i === 0)
                            @for($k = 0; $k < (($calFirstDow + 1) % 7); $k++)
                                <span></span>
                            @endfor
                        @endif
                    @endforeach
                    @foreach($calDow as $d)
                        <div class="cal-dow" title="{{ $calDowFull[$loop->index] }}">{{ $d }}</div>
                    @endforeach
                    @for($d = 1; $d <= $calDays; $d++)
                        <div class="cal-day @if($d === $calToday) today @endif">{{ $d }}</div>
                    @endfor
                </div>
            </div>

            {{-- AKTIVITAS TERBARU --}}
            <div class="app-card app-card-pad fade-up">
                <div class="flex items-center gap-3 mb-2">
                    <span class="sh-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
                    <div class="font-bold text-[#1C1C1D] text-[14.5px]">Aktivitas Terbaru</div>
                </div>
                <div class="tl mt-2">
                    @php
                        $activities = collect();
                        foreach($latestNews as $n) {
                            $activities->push(['title' => 'Artikel "' . $n->title . '" diterbitkan', 'time' => $n->created_at, 'icon' => 'fa-solid fa-newspaper', 'bg' => '#EFF6FF', 'fg' => '#2563EB']);
                        }
                        foreach($latestPrograms as $p) {
                            $activities->push(['title' => 'Program "' . $p->name . '" diperbarui', 'time' => $p->created_at, 'icon' => 'fa-solid fa-graduation-cap', 'bg' => '#FFF7ED', 'fg' => '#EA580C']);
                        }
                        foreach($latestTeachers as $t) {
                            $activities->push(['title' => 'Guru "' . $t->name . '" ditambahkan', 'time' => $t->created_at, 'icon' => 'fa-solid fa-user-tie', 'bg' => '#F0F9FF', 'fg' => '#0284C7']);
                        }
                        $activities = $activities->sortByDesc('time')->take(7);
                    @endphp
                    @forelse($activities as $act)
                        <div class="tl-item">
                            <div class="tl-dot" style="background:{{ $act['bg'] }};color:{{ $act['fg'] }}">
                                <i class="{{ $act['icon'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <div class="text-sm font-semibold text-[#1C1C1D] truncate">{{ $act['title'] }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $act['time']->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>

            {{-- PARTNER TERBARU --}}
            <div class="app-card app-card-hover overflow-hidden fade-up">
                <div class="card-head">
                    <div class="flex items-center gap-3">
                        <span class="sh-icon"><i class="fa-solid fa-handshake"></i></span>
                        <div class="font-bold text-[#1C1C1D] text-[14.5px]">Mitra Terbaru</div>
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">Semua &rarr;</a>
                </div>
                <div class="p-4 pt-2">
                    @forelse($latestPartners as $pt)
                        <div class="list-row" style="cursor:default">
                            @if($pt->logo)
                                <img src="{{ asset('storage/' . $pt->logo) }}" alt="" loading="lazy" class="w-10 h-10 rounded-xl object-contain flex-shrink-0 p-1" style="border:1px solid var(--border);background:#fff">
                            @else
                                <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#F1F5F9;color:#64748B"><i class="fa-solid fa-building"></i></span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[#1C1C1D] truncate">{{ $pt->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $pt->city }} · {{ \Carbon\Carbon::parse($pt->partnership_date)->locale('id')->translatedFormat('Y') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-sm text-gray-400">Belum ada mitra.</div>
                    @endforelse
                </div>
            </div>

            {{-- ANNOUNCEMENT --}}
            <div class="app-card app-card-pad fade-up" style="border-color:rgba(99,205,0,0.35);background:linear-gradient(135deg, #FFFFFF 0%, #F4FBEA 100%)">
                <div class="flex items-start gap-3">
                    <span class="sh-icon"><i class="fa-solid fa-bullhorn"></i></span>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-[#1C1C1D] text-[14px]">Pengumuman</div>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            @if($spmb && $spmb->status === 'Buka')
                                Pendaftaran <b>SPMB {{ $spmb->wave_name ?? 'Gelombang sekarang' }}</b> masih dibuka. Pastikan brosur & link pendaftaran sudah terisi dengan benar.
                            @else
                                Pendaftaran SPMB sedang ditutup. Nantikan gelombang berikutnya.
                            @endif
                        </p>
                        <a href="{{ route('admin.spmb_settings.edit') }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-deep hover:underline mt-2">
                            Kelola SPMB <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== SPMB BADGE ==================== --}}
    @if($spmb)
        <div class="app-card fade-up flex flex-col sm:flex-row items-center gap-4 p-5" style="border-left:4px solid {{ $spmb->status === 'Buka' ? '#63CD00' : '#9CA3AF' }}">
            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background:{{ $spmb->status === 'Buka' ? '#E8FBF0' : '#F3F4F6' }};color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">
                <i class="fa-solid fa-door-open"></i>
            </span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-bold text-[#1C1C1D]">Info SPMB</div>
                <div class="text-xs text-gray-400 mt-0.5">Status pendaftaran: <b style="color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">{{ $spmb->status }}</b> @if($spmb->wave_name) · {{ $spmb->wave_name }} @endif</div>
            </div>
            <a href="{{ route('admin.spmb_settings.edit') }}" class="app-btn app-btn-soft-brand">Atur SPMB &rarr;</a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
<script>
    window.__chartData = @json(['7d' => $chart7, '30d' => $chart30, '12m' => $chart12]);

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('visitorChart');
        if (!ctx) return;
        let chart = null;

        window.__drawChart = function (range) {
            const data = window.__chartData[range] || window.__chartData['7d'];
            const isYear = range === '12m';
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pengunjung',
                        data: data.data,
                        borderColor: '#63CD00',
                        backgroundColor: 'rgba(99,205,0,0.12)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#63CD00',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#282829',
                            titleColor: '#E5E7EB',
                            bodyColor: '#fff',
                            borderColor: '#63CD00',
                            borderWidth: 1,
                            cornerRadius: 12,
                            padding: 12,
                            bodyFont: { weight: '600' },
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#98A2B3', font: { size: 10, family: 'Poppins' }, maxRotation: 0, autoSkip: true },
                            border: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#EFF1F5' },
                            ticks: { color: '#98A2B3', font: { size: 10, family: 'Poppins' }, precision: 0 },
                            border: { display: false },
                        }
                    },
                    interaction: { mode: 'index', intersect: false },
                }
            });
        };

        window.__drawChart('7d');
    });
</script>
@endpush