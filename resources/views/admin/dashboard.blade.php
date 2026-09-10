@extends('layouts.admin-app')

@section('title', 'Dashboard')

@section('content')
<style>
    .nd { --neon: #7DFF00; --neon-dim: #339900; --navy: #0F172A; --g-bg: #F8FAFC; }
    .nd .d-card { background: #fff; border-radius: 20px; box-shadow: 0 1px 3px rgba(15,23,42,.04), 0 4px 24px rgba(15,23,42,.06); border: 1px solid #E5E7EB; transition: transform .25s, box-shadow .25s; }
    .nd .d-card:hover { transform: translateY(-2px); box-shadow: 0 2px 6px rgba(15,23,42,.06), 0 12px 36px rgba(15,23,42,.10); }
    .nd .d-stat { background: #fff; border-radius: 20px; box-shadow: 0 1px 3px rgba(15,23,42,.04), 0 4px 24px rgba(15,23,42,.06); border: 1px solid #E5E7EB; padding: 20px; transition: transform .25s, box-shadow .25s, border-color .25s; cursor: default; }
    .nd .d-stat:hover { transform: translateY(-3px); box-shadow: 0 2px 8px rgba(15,23,42,.08), 0 14px 40px rgba(15,23,42,.12); border-color: rgba(125,255,0,.35); }
    .nd .d-icon { width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; transition: background .25s, color .25s; }
    .nd .d-stat:hover .d-icon { background: var(--neon) !important; color: #fff !important; }
    .nd .d-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
    .nd .d-badge-sa { background: #F3E8FF; color: #9333EA; }
    .nd .d-badge-ad { background: #E8FBF0; color: #16A34A; }
    .nd .d-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 22px 12px; border-radius: 20px; border: 1px solid #E5E7EB; background: #fff; cursor: pointer; transition: all .25s; text-decoration: none; }
    .nd .d-btn:hover { transform: translateY(-4px); box-shadow: 0 4px 16px rgba(15,23,42,.08), 0 16px 48px rgba(15,23,42,.12); border-color: var(--neon); }
    .nd .d-btn:hover .d-btn-icon { background: var(--neon); color: #fff; }
    .nd .d-btn-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; transition: all .25s; }
    .nd .d-btn span:last-child { font-size: 12px; font-weight: 600; color: #4B5563; }
    .nd .d-timeline-item { display: flex; gap: 14px; padding: 14px 0; position: relative; }
    .nd .d-timeline-item:not(:last-child)::after { content: ''; position: absolute; left: 17px; top: 50px; bottom: -2px; width: 2px; background: #E5E7EB; }
    .nd .d-tl-dot { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; z-index: 1; }
    .nd .d-online { display: flex; align-items: center; gap: 10px; padding: 10px 0; }
    .nd .d-online:not(:last-child) { border-bottom: 1px solid #F3F4F6; }
    .nd .d-ro { padding: 12px 14px; border-radius: 14px; background: #F8FAFC; border: 1px solid #E5E7EB; }
    .nd .d-ro-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #9CA3AF; }
    .nd .d-ro-value { font-size: 13px; font-weight: 600; color: #0F172A; margin-top: 2px; }
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .5s ease, transform .5s ease; }
    .fade-up.vis { opacity: 1; transform: translateY(0); }
</style>

<div class="nd">

    {{-- ===================== PROFILE CARD ===================== --}}
    @php
        $userName = Auth::user()->name;
        $userEmail = Auth::user()->email;
        $userRole = Auth::user()->role;
        $userInitials = strtoupper(collect(explode(' ', trim($userName)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode(''));
        $hasAvatar = Auth::user()->avatar && \Storage::disk('public')->exists(Auth::user()->avatar);
        $isOnline = optional(Auth::user()->session)->last_activity && (time() - Auth::user()->session->last_activity) < 300;
    @endphp

    <div class="d-card fade-up p-6 mb-8">
        <div class="flex flex-col lg:flex-row items-center gap-6">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                @if($hasAvatar)
                    <img src="{{ \Storage::disk('public')->url(Auth::user()->avatar) }}" alt="{{ $userName }}"
                        class="w-14 h-14 rounded-full object-cover flex-shrink-0" style="border:3px solid #7DFF00" />
                @else
                    <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 text-white text-lg font-bold"
                        style="background:linear-gradient(135deg,#7DFF00,#339900)">
                        {{ $userInitials }}
                    </div>
                @endif
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-[#0F172A] truncate">{{ $userName }}</h2>
                    <p class="text-sm text-gray-500 truncate">{{ $userEmail }}</p>
                </div>
                <span class="d-badge {{ $userRole === 'superadmin' ? 'd-badge-sa' : 'd-badge-ad' }} ml-3 flex-shrink-0 hidden sm:inline-flex">
                    @if($userRole === 'superadmin')
                        <i class="fa-solid fa-crown"></i> SUPER ADMIN
                    @else
                        <i class="fa-solid fa-shield-halved"></i> ADMIN
                    @endif
                </span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isOnline ? 'bg-[#e8fbf0] text-[#16A34A]' : 'bg-gray-100 text-gray-500' }}">
                    <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-[#16A34A] animate-pulse' : 'bg-gray-400' }}"></span>
                    {{ $isOnline ? 'Session Aktif' : 'Offline' }}
                </span>
            </div>
            <div class="hidden lg:flex items-center justify-center w-16 h-16 rounded-2xl flex-shrink-0" style="background:rgba(125,255,0,.1)">
                <i class="fa-solid fa-shield-halved text-3xl" style="color:rgba(125,255,0,.5)"></i>
            </div>
        </div>
    </div>

    {{-- ===================== GREETING ===================== --}}
    <div class="mb-8 fade-up">
        <h1 class="text-3xl lg:text-4xl font-bold text-[#0F172A]">Selamat Datang,<br class="lg:hidden">{{ $userName }} 👋</h1>
        <p class="text-gray-500 mt-2 text-base">Kelola seluruh sistem sekolah melalui dashboard administrator.</p>
    </div>

    {{-- ===================== STATISTIK ===================== --}}
@php
    $diskFree  = @disk_free_space('/')  ? round(@disk_free_space('/') / 1073741824, 1) : 0;
    $diskTotal = @disk_total_space('/') ? round(@disk_total_space('/') / 1073741824, 1) : 0;
    $diskUsed  = max(0, $diskTotal - $diskFree);
    $diskPct   = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;

    // Ukur ukuran folder storage/app/public secara rekursif
    $storageBytes = 0;
    $storageRoot = storage_path('app/public');
    if (is_dir($storageRoot)) {
        $rit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageRoot, FilesystemIterator::SKIP_DOTS));
        foreach ($rit as $file) {
            if ($file->isFile()) $storageBytes += $file->getSize();
        }
    }
    $storageMb = round($storageBytes / 1048576, 1);

    $monthVisitors = \App\Models\TrafficVisitor::whereMonth('created_at', now()->month)->count();

        $statItems = [
            ['label' => 'Total User', 'value' => $stats['users'], 'icon' => 'fa-solid fa-users', 'bg' => '#F3E8FF', 'fg' => '#9333EA'],
            ['label' => 'Total Artikel', 'value' => $stats['news'], 'icon' => 'fa-solid fa-newspaper', 'bg' => '#EFF6FF', 'fg' => '#2563EB'],
            ['label' => 'Total Gallery', 'value' => $stats['images'], 'icon' => 'fa-solid fa-images', 'bg' => '#F0F9FF', 'fg' => '#0284C7'],
            ['label' => 'Program Pendidikan', 'value' => $stats['programs'], 'icon' => 'fa-solid fa-graduation-cap', 'bg' => '#FFF7ED', 'fg' => '#EA580C'],
            ['label' => 'Pengunjung Hari Ini', 'value' => number_format($stats['visitorsToday']), 'icon' => 'fa-solid fa-chart-line', 'bg' => '#E8FBF0', 'fg' => '#16A34A'],
            ['label' => 'Visitor Bulan Ini', 'value' => number_format($monthVisitors), 'icon' => 'fa-solid fa-globe', 'bg' => '#FDF4FF', 'fg' => '#C026D3'],
            ['label' => 'Kapasitas Hosting', 'value' => $diskPct . '%', 'icon' => 'fa-solid fa-hard-drive', 'bg' => '#FEF2F2', 'fg' => '#DC2626', 'meta' => $diskUsed . ' / ' . $diskTotal . ' GB', 'pct' => $diskPct],
            ['label' => 'Penggunaan Storage', 'value' => $storageMb . ' MB', 'icon' => 'fa-solid fa-database', 'bg' => '#F8FAFC', 'fg' => '#475569'],
        ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
        @foreach($statItems as $s)
            <div class="d-stat fade-up">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ $s['label'] }}</div>
                        <div class="text-2xl lg:text-3xl font-extrabold text-[#0F172A] mt-1">{{ $s['value'] }}</div>
                    </div>
                    <div class="d-icon" style="background:{{ $s['bg'] }};color:{{ $s['fg'] }}">
                        <i class="{{ $s['icon'] }}"></i>
                    </div>
                </div>
                @if(isset($s['pct']))
                    <div class="mt-3 w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700" style="width:{{ $s['pct'] }}%;background:{{ $s['pct'] > 85 ? '#DC2626' : '#7DFF00' }}"></div>
                    </div>
                @endif
                @if(isset($s['meta']))
                    <div class="text-xs text-gray-400 mt-2">{{ $s['meta'] }}</div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ===================== QUICK ACTIONS ===================== --}}
    <div class="d-card fade-up p-6 mb-8">
        <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
            <i class="fa-solid fa-bolt" style="color:#7DFF00"></i> Aksi Cepat
        </h3>
        @php
            $quickActions = [
                ['label' => 'Tambah Artikel', 'icon' => 'fa-solid fa-newspaper', 'url' => route('admin.news.create'), 'bg' => '#EFF6FF', 'fg' => '#2563EB'],
                ['label' => 'Kelola Program', 'icon' => 'fa-solid fa-graduation-cap', 'url' => route('admin.programs.index'), 'bg' => '#FFF7ED', 'fg' => '#EA580C'],
                ['label' => 'Guru & Staf', 'icon' => 'fa-solid fa-user-tie', 'url' => route('admin.teachers.index'), 'bg' => '#F0F9FF', 'fg' => '#0284C7'],
                ['label' => 'Upload Galeri', 'icon' => 'fa-solid fa-images', 'url' => route('admin.image.index'), 'bg' => '#FDF4FF', 'fg' => '#C026D3'],
                ['label' => 'Feed Instagram', 'icon' => 'fa-brands fa-instagram', 'url' => route('admin.insta-posts.index'), 'bg' => '#FEF2F2', 'fg' => '#E11D48'],
                ['label' => 'Info SPMB', 'icon' => 'fa-solid fa-file-circle-check', 'url' => route('admin.spmb_settings.edit'), 'bg' => '#E8FBF0', 'fg' => '#16A34A'],
            ];
        @endphp
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($quickActions as $qa)
                <a href="{{ $qa['url'] }}" class="d-btn">
                    <div class="d-btn-icon" style="background:{{ $qa['bg'] }};color:{{ $qa['fg'] }}">
                        <i class="{{ $qa['icon'] }}"></i>
                    </div>
                    <span>{{ $qa['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===================== CONTENT: 2/3 + 1/3 ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- ===== KIRI (2/3) ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- GRAFIK PENGUNJUNG --}}
            <div class="d-card fade-up p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-chart-area" style="color:#7DFF00"></i> Statistik Pengunjung
                    </h3>
                    <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1" x-data="{ range: '7d' }">
                        <button @click="range='7d'" :class="range==='7d' ? 'bg-white shadow text-[#0F172A]' : 'text-gray-500'" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">7 Hari</button>
                        <button @click="range='30d'" :class="range==='30d' ? 'bg-white shadow text-[#0F172A]' : 'text-gray-500'" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">30 Hari</button>
                        <button @click="range='12m'" :class="range==='12m' ? 'bg-white shadow text-[#0F172A]' : 'text-gray-500'" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">12 Bulan</button>
                    </div>
                </div>
                <div class="relative" style="height:260px">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>

            {{-- BERITA TERBARU --}}
            <div class="d-card fade-up">
                <div class="flex items-center justify-between px-6 pt-5">
                    <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-newspaper" style="color:#2563EB"></i> Berita Terbaru
                    </h3>
                    <a href="{{ route('admin.news.index') }}" class="text-xs font-semibold hover:underline" style="color:#7DFF00">Lihat semua →</a>
                </div>
                <div class="px-6 pb-4">
                    @forelse($latestNews as $n)
                        <a href="{{ route('admin.news.edit', $n->id) }}" class="flex items-center gap-4 py-3 transition-all" style="border-top:1px solid #F3F4F6" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            @if($n->image)
                                <img src="{{ asset('storage/' . $n->image) }}" alt="" loading="lazy" class="w-11 h-11 rounded-xl object-cover flex-shrink-0" style="border:1px solid #E5E7EB">
                            @else
                                <span class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF;color:#2563EB"><i class="fa-regular fa-file-lines"></i></span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[#0F172A] truncate">{{ $n->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($n->date_published)->locale('id')->translatedFormat('d M Y') }} · {{ $n->publisher }}</div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 flex-shrink-0"></i>
                        </a>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-400">Belum ada berita. <a href="{{ route('admin.news.create') }}" style="color:#7DFF00;font-weight:600">Tulis yang pertama →</a></div>
                    @endforelse
                </div>
            </div>

            {{-- PROGRAM TERBARU --}}
            <div class="d-card fade-up">
                <div class="flex items-center justify-between px-6 pt-5">
                    <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap" style="color:#9333EA"></i> Program Pendidikan
                    </h3>
                    <a href="{{ route('admin.programs.index') }}" class="text-xs font-semibold hover:underline" style="color:#7DFF00">Lihat semua →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6">
                    @forelse($latestPrograms as $p)
                        <a href="{{ route('admin.programs.edit', $p->id) }}" class="d-card overflow-hidden">
                            <div style="aspect-ratio:16/8;overflow:hidden;background:#F1F5F9">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background:#F8FAFC"><i class="fa-solid fa-graduation-cap text-gray-300 text-xl"></i></div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-bold text-[#0F172A] truncate">{{ $p->name }}</span>
                                    @include('admin.components.status-badge', ['status' => $p->status])
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-8 text-center text-sm text-gray-400">Belum ada program.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ===== KANAN (1/3) ===== --}}
        <div class="space-y-6">

            {{-- INFORMASI SISTEM --}}
            <div class="d-card fade-up p-6">
                <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-server" style="color:#7DFF00"></i> Informasi Sistem
                </h3>
                @php
                    $sysInfo = [
                        ['label' => 'Versi Laravel', 'value' => app()->version(), 'icon' => 'fa-solid fa-code'],
                        ['label' => 'Versi PHP', 'value' => phpversion(), 'icon' => 'fa-solid fa-code'],
                        ['label' => 'Database', 'value' => config('database.connections.'.config('database.default').'.driver', 'N/A'), 'icon' => 'fa-solid fa-database'],
                        ['label' => 'Server', 'value' => php_uname('n'), 'icon' => 'fa-solid fa-server'],
                        ['label' => 'Memory Usage', 'value' => round(memory_get_usage(true) / 1048576, 1) . ' MB', 'icon' => 'fa-solid fa-memory'],
                        ['label' => 'Disk Free', 'value' => $diskFree . ' GB / ' . $diskTotal . ' GB', 'icon' => 'fa-solid fa-hard-drive'],
                    ];
                @endphp
                <div class="space-y-3">
                    @foreach($sysInfo as $si)
                        <div class="d-ro">
                            <div class="flex items-center gap-2">
                                <i class="{{ $si['icon'] }} text-[11px]" style="color:#7DFF00"></i>
                                <span class="d-ro-label">{{ $si['label'] }}</span>
                            </div>
                            <div class="d-ro-value">{{ $si['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ADMIN ONLINE --}}
            <div class="d-card fade-up p-6">
                <h3 class="text-base font-bold text-[#0F172A] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle" style="color:#16A34A;font-size:8px"></i> Admin Online
                </h3>
                @php
                    $onlineUsers = $recentUsers->filter(fn($u) => optional($u->session)->last_activity && (time() - $u->session->last_activity) < 300);
                @endphp
                @if($onlineUsers->isNotEmpty())
                    @foreach($onlineUsers as $ou)
                        <div class="d-online">
                            @if($ou->avatar && \Storage::disk('public')->exists($ou->avatar))
                                <img src="{{ \Storage::disk('public')->url($ou->avatar) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0" style="border:2px solid #7DFF00" alt="">
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold"
                                    style="background:linear-gradient(135deg,#7DFF00,#339900)">
                                    {{ strtoupper(collect(explode(' ', $ou->name))->take(2)->map(fn($w)=>mb_substr($w,0,1))->implode('')) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[#0F172A] truncate">{{ $ou->name }}</div>
                                <div class="text-xs text-gray-400 capitalize">{{ $ou->role }}</div>
                            </div>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse flex-shrink-0"></span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-sm text-gray-400">Tidak ada admin yang online.</div>
                @endif
            </div>

            {{-- TRAFFIC MINI --}}
            <div class="d-card fade-up p-6">
                <h3 class="text-base font-bold text-[#0F172A] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple" style="color:#7DFF00"></i> Traffic Website
                </h3>
                @php
                    $todayVisitors = $stats['visitorsToday'];
                    $weekVisitors = \App\Models\TrafficVisitor::where('created_at', '>=', now()->startOfWeek())->count();
                    $monthTraffic = $monthVisitors;
                    $totalPV = \App\Models\TrafficVisitor::count();
                @endphp
                <div class="space-y-3">
                    <div class="d-ro">
                        <div class="flex items-center justify-between">
                            <span class="d-ro-label">Hari Ini</span>
                            <span class="text-sm font-bold text-[#0F172A]">{{ number_format($todayVisitors) }}</span>
                        </div>
                    </div>
                    <div class="d-ro">
                        <div class="flex items-center justify-between">
                            <span class="d-ro-label">Minggu Ini</span>
                            <span class="text-sm font-bold text-[#0F172A]">{{ number_format($weekVisitors) }}</span>
                        </div>
                    </div>
                    <div class="d-ro">
                        <div class="flex items-center justify-between">
                            <span class="d-ro-label">Bulan Ini</span>
                            <span class="text-sm font-bold text-[#0F172A]">{{ number_format($monthTraffic) }}</span>
                        </div>
                    </div>
                    <div class="d-ro">
                        <div class="flex items-center justify-between">
                            <span class="d-ro-label">Total Page Views</span>
                            <span class="text-sm font-bold text-[#0F172A]">{{ number_format($totalPV) }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.traffic.index') }}" class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold transition-all" style="background:#E8FBF0;color:#16A34A;border:1px solid #BBF7D0" onmouseover="this.style.background='#16A34A';this.style.color='#fff'" onmouseout="this.style.background='#E8FBF0';this.style.color='#16A34A'">
                    <i class="fa-solid fa-chart-line"></i> Buka Traffic
                </a>
            </div>

            {{-- AKTIVITAS TERBARU --}}
            <div class="d-card fade-up p-6">
                <h3 class="text-base font-bold text-[#0F172A] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left" style="color:#7DFF00"></i> Aktivitas Terbaru
                </h3>
                @php
                    $activities = collect();
                    foreach($latestNews as $n) {
                        $activities->push(['type' => 'news', 'title' => "Artikel \"{$n->title}\" dipublish", 'time' => $n->created_at, 'icon' => 'fa-solid fa-newspaper', 'bg' => '#EFF6FF', 'fg' => '#2563EB']);
                    }
                    foreach($latestPrograms as $p) {
                        $activities->push(['type' => 'program', 'title' => "Program \"{$p->name}\" diperbarui", 'time' => $p->created_at, 'icon' => 'fa-solid fa-graduation-cap', 'bg' => '#FFF7ED', 'fg' => '#EA580C']);
                    }
                    foreach($recentUsers as $u) {
                        $activities->push(['type' => 'user', 'title' => "Admin \"{$u->name}\" bergabung", 'time' => $u->created_at, 'icon' => 'fa-solid fa-user-plus', 'bg' => '#F3E8FF', 'fg' => '#9333EA']);
                    }
                    $activities = $activities->sortByDesc('time')->take(8);
                @endphp
                @forelse($activities as $act)
                    <div class="d-timeline-item">
                        <div class="d-tl-dot" style="background:{{ $act['bg'] }};color:{{ $act['fg'] }}">
                            <i class="{{ $act['icon'] }}"></i>
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <div class="text-sm font-semibold text-[#0F172A] truncate">{{ $act['title'] }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $act['time']->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-gray-400">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===================== INFO SPMB BADGE ===================== --}}
    @if($spmb)
        <div class="d-card fade-up p-5 flex flex-col sm:flex-row items-center gap-4 mb-8" style="border-left:4px solid {{ $spmb->status === 'Buka' ? '#7DFF00' : '#9CA3AF' }}">
            <div class="flex items-center gap-3 flex-1">
                <span class="w-11 h-11 rounded-xl flex items-center justify-center text-xl" style="background:{{ $spmb->status === 'Buka' ? '#E8FBF0' : '#F3F4F6' }};color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">
                    <i class="fa-solid fa-door-open"></i>
                </span>
                <div>
                    <div class="text-sm font-bold text-[#0F172A]">Info SPMB</div>
                    <div class="text-xs text-gray-400">Status pendaftaran: <span class="font-semibold" style="color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">{{ $spmb->status }}</span></div>
                </div>
            </div>
            <a href="{{ route('admin.spmb_settings.edit') }}" class="text-xs font-semibold px-4 py-2 rounded-xl transition-all" style="background:#E8FBF0;color:#16A34A;border:1px solid #BBF7D0" onmouseover="this.style.background='#16A34A';this.style.color='#fff'" onmouseout="this.style.background='#E8FBF0';this.style.color='#16A34A'">
                Atur SPMB →
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
<script>
    // Fade-up observer
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.fade-up').forEach((el, i) => {
            setTimeout(() => el.classList.add('vis'), 60 * i);
        });
    });

    // Date display
    var dateEl = document.getElementById('current-date');
    if (dateEl) {
        var fmt = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        dateEl.textContent = fmt.format(new Date());
    }

    // Chart.js — Visitor Chart
    @php
        $chartDays = 7;
        $chartLabels = [];
        $chartData = [];
        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartLabels[] = $day->locale('id')->isoFormat('dd DD');
            $chartData[] = \App\Models\TrafficVisitor::whereDate('created_at', $day)->count();
        }
    @endphp

    const ctx = document.getElementById('visitorChart');
    let visitorChart = null;

    function buildChart(labels, data) {
        if (visitorChart) visitorChart.destroy();
        visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengunjung',
                    data: data,
                    borderColor: '#7DFF00',
                    backgroundColor: 'rgba(125,255,0,.1)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#7DFF00',
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
                        backgroundColor: '#0F172A',
                        titleColor: '#E5E7EB',
                        bodyColor: '#fff',
                        borderColor: '#7DFF00',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        bodyFont: { weight: '600' },
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9CA3AF', font: { size: 11, family: 'Poppins' } },
                        border: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#9CA3AF', font: { size: 11, family: 'Poppins' }, stepSize: 1 },
                        border: { display: false },
                    }
                },
                interaction: { mode: 'index', intersect: false },
            }
        });
    }

    buildChart(@json($chartLabels), @json($chartData));
</script>
@endpush