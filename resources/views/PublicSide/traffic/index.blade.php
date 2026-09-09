@extends('layouts.public-app')

@section('description', __('Statistik kunjungan pengunjung website SMK Amaliah 1 & 2 Ciawi.'))
@section('title', __('Statistik Kunjungan Website') . ' - SMK Amaliah')

@section('content')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        @php
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';
            $fmt = fn ($n) => number_format((float) $n, 0, ',', '.');
        @endphp

        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold" style="color: {{ $amaliahDark }};">{{ __('Statistik Kunjungan Website') }}</h1>
            <p class="text-slate-500 mt-3 text-base">
                {{ __('Rekapan kunjungan pengunjung SMK Amaliah dalam 1 bulan terakhir.') }}
            </p>
        </div>

        {{-- Statistik Hari Ini --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('Pengunjung Hari Ini') }}</p>
                <p class="mt-2 text-3xl font-bold" style="color: {{ $amaliahGreen }};">{{ $fmt($today['visitors']) }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $today['date'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('Akses Halaman Hari Ini') }}</p>
                <p class="mt-2 text-3xl font-bold" style="color: {{ $amaliahDark }};">{{ $fmt($today['views']) }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ __('Total tampilan halaman') }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('Klik Hari Ini') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">{{ $fmt($today['clicks']) }}</p>
                <p class="text-xs text-slate-400 mt-1">
                    <span style="color: {{ $amaliahGreen }};">{{ $fmt($today['link_clicks']) }} {{ __('link') }}</span> ·
                    <span class="text-gray-700">{{ $fmt($today['button_clicks']) }} {{ __('button') }}</span>
                </p>
            </div>
        </div>

        {{-- Grafik 30 hari terakhir --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-12">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700">{{ __('Pengunjung per Hari') }}</h3>
                <span class="text-xs text-slate-400 font-medium">{{ __('30 Hari Terakhir') }}</span>
            </div>
            <div class="p-5">
                @if ($chart && ! empty($chart['days']))
                    <div class="relative w-full" style="height: 260px;">
                        <canvas id="chartDaily"></canvas>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 text-xs">
                        <span class="text-slate-500 font-medium">{{ __('Total pengunjung 30 hari:') }}
                            <b style="color: {{ $amaliahGreen }};">{{ $fmt($chart['total']) }}</b></span>
                        <span class="text-slate-400 font-medium">{{ count($chart['days']) }} {{ __('hari') }}</span>
                    </div>
                @else
                    <p class="text-sm text-slate-400 text-center py-8">{{ __('Belum ada data kunjungan.') }}</p>
                @endif
            </div>
        </div>

        {{-- Rekapan Bulanan --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700">{{ __('Rekapan Bulanan') }}</h3>
                <span class="text-xs text-slate-400 font-medium">{{ __('12 Bulan Terakhir') }}</span>
            </div>
            <div class="p-5">
                <div class="relative w-full" style="height: 260px;">
                    <canvas id="chartMonthly"></canvas>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-gray-100 text-xs font-semibold uppercase tracking-wider">
                    <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm" style="background: {{ $amaliahGreen }};"></span> {{ __('Pengunjung') }}</span>
                    <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-gray-800"></span> {{ __('Klik') }}</span>
                    <span class="sm:ml-auto text-slate-500 normal-case font-bold">{{ __('Total 12 bulan:') }}
                        <span class="text-base" style="color: {{ $amaliahGreen }};">{{ $fmt($monthly->sum('visitors')) }}</span> {{ __('pengunjung') }} ·
                        <span class="text-base text-gray-800">{{ $fmt($monthly->sum('clicks')) }}</span> {{ __('klik') }}</span>
                </div>
            </div>
        </div>

        {{-- Link ke data lengkap (admin) --}}
        @auth
            <div class="text-center mt-10">
                <p class="text-slate-500 text-sm mb-3">{{ __('Ingin melihat statistik kunjungan lebih lengkap (detail link, button, dan pengunjung)?') }}</p>
                <a href="{{ route('admin.traffic.index') }}"
                    class="inline-flex items-center bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition-colors shadow-md">
                    <i class="fa-solid fa-chart-line mr-2"></i> {{ __('Lihat Statistik Lengkap') }}
                </a>
            </div>
        @endauth
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        const nf = new Intl.NumberFormat('id-ID');

        function fmtDate(dateStr) {
            return new Date(dateStr + 'T00:00:00')
                .toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }

        const tooltipBase = {
            backgroundColor: '#282829',
            titleColor: '#ffffff',
            bodyColor: '#e2e8f0',
            padding: 12,
            cornerRadius: 10,
            boxPadding: 6,
            usePointStyle: true,
        };

        const scX = {
            grid: { display: false },
            border: { display: false },
            ticks: { color: '#94a3b8', maxRotation: 0, autoSkipPadding: 8, font: { size: 10 } }
        };
        const scY = {
            beginAtZero: true,
            border: { display: false },
            grid: { color: 'rgba(226,232,240,.6)' },
            ticks: { color: '#94a3b8', precision: 0, font: { size: 10 } }
        };

        // Grafik harian (area line)
        const chartDaily = document.getElementById('chartDaily');
        if (chartDaily) {
            const days = @json($chart['days']);
            new Chart(chartDaily, {
                type: 'line',
                data: {
                    labels: days.map(d => d.label),
                    datasets: [{
                        label: @json(__('Pengunjung')),
                        data: days.map(d => d.visitors),
                        borderColor: '#63cd00',
                        backgroundColor: function (ctx) {
                            const chart = ctx.chart;
                            const { ctx: c, chartArea } = chart;
                            if (!chartArea) return 'rgba(99,205,0,0.1)';
                            const g = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            g.addColorStop(0, 'rgba(99,205,0,0.05)');
                            g.addColorStop(1, 'rgba(99,205,0,0.35)');
                            return g;
                        },
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#63cd00',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointHitRadius: 12,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1400, easing: 'easeOutQuart' },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: Object.assign({}, tooltipBase, {
                            callbacks: {
                                title: (t) => fmtDate(days[t[0].dataIndex].date),
                                label: (c) => ' ' + @json(__('Pengunjung')) + ': ' + nf.format(c.raw),
                            }
                        })
                    },
                    scales: { x: scX, y: scY }
                }
            });
        }

        // Grafik rekapan bulanan (grouped bar)
        const chartMonthly = document.getElementById('chartMonthly');
        if (chartMonthly) {
            const monthly = @json($monthly);
            new Chart(chartMonthly, {
                type: 'bar',
                data: {
                    labels: monthly.map(m => m.label),
                    datasets: [{
                        label: @json(__('Pengunjung')),
                        data: monthly.map(m => m.visitors),
                        backgroundColor: function (ctx) {
                            const { ctx: c, chartArea } = ctx.chart;
                            if (!chartArea) return '#63cd00';
                            const g = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            g.addColorStop(0, 'rgba(99,205,0,0.15)');
                            g.addColorStop(1, '#63cd00');
                            return g;
                        },
                        hoverBackgroundColor: '#53b800',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 24,
                    }, {
                        label: @json(__('Klik')),
                        data: monthly.map(m => m.clicks),
                        backgroundColor: '#1e1e1e',
                        hoverBackgroundColor: '#3f3f46',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1200, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: Object.assign({}, tooltipBase, {
                            callbacks: {
                                title: (t) => monthly[t[0].dataIndex].label,
                                label: (c) => ' ' + c.dataset.label + ': ' + nf.format(c.raw),
                            }
                        })
                    },
                    scales: { x: scX, y: scY }
                }
            });
        }
    </script>
@endsection
