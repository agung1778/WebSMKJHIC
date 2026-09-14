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

    {{-- ==================== STATISTIK ==================== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Artikel',     'value' => number_format($stats['news']),           'icon' => 'fa-solid fa-newspaper', 'bg' => '#EFF6FF', 'fg' => '#2563EB', 'sub' => 'Berita terpublish'],
            ['label' => 'Jumlah Guru',       'value' => number_format($stats['teachers']),       'icon' => 'fa-solid fa-user-tie',  'bg' => '#F0F9FF', 'fg' => '#0284C7', 'sub' => 'Tenaga pendidik'],
            ['label' => 'Jumlah Prestasi',   'value' => number_format($stats['achievements']),   'icon' => 'fa-solid fa-trophy',    'bg' => '#FFFBEB', 'fg' => '#D97706', 'sub' => 'Raihan siswa & sekolah'],
            ['label' => 'Jumlah Jurusan',    'value' => number_format($stats['majors']),         'icon' => 'fa-solid fa-layer-group','bg' => '#F5F3FF', 'fg' => '#7C3AED', 'sub' => 'Kompetensi keahlian'],
            ['label' => 'Jumlah Siswa',      'value' => number_format($stats['students']),       'icon' => 'fa-solid fa-user-graduate','bg' => '#F0FDFA', 'fg' => '#0D9488', 'sub' => 'Berdasarkan data sekolah'],
            ['label' => 'Jumlah Fasilitas',  'value' => number_format($stats['facilities']),     'icon' => 'fa-solid fa-building',  'bg' => '#FFFBEB', 'fg' => '#CA8A04', 'sub' => 'Sarana & prasarana'],
            ['label' => 'Pengunjung Hari Ini','value' => number_format($stats['visitorsToday']),'icon' => 'fa-solid fa-chart-line','bg' => '#E8FBF0', 'fg' => '#16A34A', 'sub' => 'Visitor tercatat'],
            ['label' => 'Visitor Bulan Ini', 'value' => number_format($monthVisitors),           'icon' => 'fa-solid fa-globe',      'bg' => '#FFF7ED', 'fg' => '#EA580C', 'sub' => 'Total bulan ' . now()->locale('id')->isoFormat('MMMM')],
        ] as $st)
            <x-admin-components::stat-card :label="$st['label']" :value="$st['value']" :icon="$st['icon']" :bg="$st['bg']" :fg="$st['fg']" :sub="$st['sub']" />
        @endforeach
    </div>

    {{-- ==================== GRAFIK PENGUNJUNG ==================== --}}
    <div class="app-card app-card-pad fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
            <div class="flex items-center gap-3">
                <span class="sh-icon" style="width:38px;height:38px;border-radius:12px;background:var(--brand-soft);color:var(--brand-deep);display:flex;align-items:center;justify-content:center">
                    <i class="fa-solid fa-chart-area"></i>
                </span>
                <div>
                    <div class="dash-title" style="font-size:14.5px">Statistik Pengunjung</div>
                    <div class="dash-sub">Aktivitas visitor website</div>
                </div>
            </div>
            <div class="range-tabs" x-data="{ range: '7d' }">
                <button :class="range==='7d' && 'active'" @click="range='7d'; window.__drawChart('7d')">7 Hari</button>
                <button :class="range==='30d' && 'active'" @click="range='30d'; window.__drawChart('30d')">30 Hari</button>
                <button :class="range==='12m' && 'active'" @click="range='12m'; window.__drawChart('12m')">12 Bulan</button>
            </div>
        </div>
        <div style="height:300px;position:relative">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>

    {{-- ==================== AKSI CEPAT ==================== --}}
    <div class="app-card app-card-pad fade-up">
        <div class="flex items-center gap-3 mb-5">
            <span class="sh-icon" style="width:38px;height:38px;border-radius:12px;background:var(--brand-soft);color:var(--brand-deep);display:flex;align-items:center;justify-content:center">
                <i class="fa-solid fa-bolt"></i>
            </span>
            <div>
                <div class="dash-title" style="font-size:14.5px">Aksi Cepat</div>
                <div class="dash-sub">Tambahkan konten baru dalam sekali klik</div>
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

    {{-- ==================== SPMB BADGE ==================== --}}
    @if($spmb)
        <div class="app-card fade-up flex flex-col sm:flex-row items-center gap-4 p-5" style="border-left:4px solid {{ $spmb->status === 'Buka' ? '#63CD00' : '#9CA3AF' }}">
            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background:{{ $spmb->status === 'Buka' ? 'var(--green-soft)' : 'var(--surface-3)' }};color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">
                <i class="fa-solid fa-door-open"></i>
            </span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-bold dash-title">Info SPMB</div>
                <div class="text-xs dash-sub mt-0.5">Status pendaftaran: <b style="color:{{ $spmb->status === 'Buka' ? '#16A34A' : '#9CA3AF' }}">{{ $spmb->status }}</b> @if($spmb->wave_name) · {{ $spmb->wave_name }} @endif</div>
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
    window.__chartRange = '7d';

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('visitorChart');
        if (!ctx) return;
        let chart = null;

        const cssVar = (name, fallback) => {
            const v = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            return v || fallback;
        };

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
                            backgroundColor: cssVar('--surface-3', '#282829'),
                            titleColor: cssVar('--text', '#E5E7EB'),
                            bodyColor: cssVar('--text', '#fff'),
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
                            ticks: { color: cssVar('--text-3', '#98A2B3'), font: { size: 10, family: 'Poppins' }, maxRotation: 0, autoSkip: true },
                            border: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: cssVar('--border', '#EFF1F5') },
                            ticks: { color: cssVar('--text-3', '#98A2B3'), font: { size: 10, family: 'Poppins' }, precision: 0 },
                            border: { display: false },
                        }
                    },
                    interaction: { mode: 'index', intersect: false },
                }
            });
        };

        window.__drawChart(window.__chartRange);

        const toggle = document.getElementById('theme-toggle');
        const onToggle = () => window.__drawChart(window.__chartRange);
        if (toggle) toggle.addEventListener('click', onToggle);
    });
</script>
@endpush