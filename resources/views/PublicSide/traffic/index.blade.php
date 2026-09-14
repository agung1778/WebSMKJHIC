@extends('layouts.public-app')

@section('description', __('Statistik kunjungan per hari, per minggu, dan per bulan pengunjung website SMK Amaliah 1 & 2 Ciawi.'))
@section('title', __('Statistik Kunjungan Website') . ' - SMK Amaliah')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        @php
            $amaliahGreen = '#63cd00';
            $amaliahDark = '#282829';
            $fmt = fn ($n) => number_format((float) $n, 0, ',', '.');
            $weeklyTotal = ['visitors' => (int) $weekly->sum('visitors'), 'clicks' => (int) $weekly->sum('clicks')];
            $monthlyTotal = ['visitors' => (int) $monthly->sum('visitors'), 'clicks' => (int) $monthly->sum('clicks')];
        @endphp

        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold" style="color: {{ $amaliahDark }};">{{ __('Statistik Kunjungan Website') }}</h1>
            <p class="text-slate-500 mt-3 text-base">
                {{ __('Pantau kunjungan pengunjung SMK Amaliah per hari, per minggu, dan per bulan.') }}
            </p>
        </div>

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

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-sm font-bold text-slate-700">{{ __('Grafik Kunjungan') }}</h3>
                <div class="flex rounded-xl bg-gray-100 p-1 gap-1" id="periodTabs">
                    <button type="button" data-chart-tab="daily"
                        class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-gray-900 shadow">{{ __('Harian') }}</button>
                    <button type="button" data-chart-tab="weekly"
                        class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all text-gray-500 hover:text-gray-700">{{ __('Mingguan') }}</button>
                    <button type="button" data-chart-tab="monthly"
                        class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all text-gray-500 hover:text-gray-700">{{ __('Bulanan') }}</button>
                </div>
            </div>
            <div class="p-5">
                <div class="relative w-full" style="height: 300px;" id="panel-daily">
                    <canvas id="chartDaily"></canvas>
                </div>
                <div class="relative w-full" style="height: 300px; display: none;" id="panel-weekly">
                    <canvas id="chartWeekly"></canvas>
                </div>
                <div class="relative w-full" style="height: 300px; display: none;" id="panel-monthly">
                    <canvas id="chartMonthly"></canvas>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-gray-100 text-xs font-semibold uppercase tracking-wider" id="chartFooter">
                    <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm" style="background: {{ $amaliahGreen }};"></span> {{ __('Pengunjung') }}</span>
                    <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-gray-800"></span> {{ __('Klik') }}</span>
                    <span class="sm:ml-auto normal-case" data-summary="daily">
                        {{ __('Total 30 hari:') }}
                        <span class="text-base" style="color: {{ $amaliahGreen }};">{{ $fmt($chart['total']) }}</span> {{ __('pengunjung') }} ·
                        <span class="text-base text-gray-800">{{ $fmt($chart['total']) }}</span> {{ __('kunjungan') }}
                    </span>
                    <span class="sm:ml-auto normal-case hidden" data-summary="weekly">
                        {{ __('Total 8 minggu:') }}
                        <span class="text-base" style="color: {{ $amaliahGreen }};">{{ $fmt($weeklyTotal['visitors']) }}</span> {{ __('pengunjung') }} ·
                        <span class="text-base text-gray-800">{{ $fmt($weeklyTotal['clicks']) }}</span> {{ __('klik') }}
                    </span>
                    <span class="sm:ml-auto normal-case hidden" data-summary="monthly">
                        {{ __('Total 12 bulan:') }}
                        <span class="text-base" style="color: {{ $amaliahGreen }};">{{ $fmt($monthlyTotal['visitors']) }}</span> {{ __('pengunjung') }} ·
                        <span class="text-base text-gray-800">{{ $fmt($monthlyTotal['clicks']) }}</span> {{ __('klik') }}
                    </span>
                </div>
            </div>
        </div>

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

        const tabBtns = document.querySelectorAll('[data-chart-tab]');
        const panels = {
            daily: document.getElementById('panel-daily'),
            weekly: document.getElementById('panel-weekly'),
            monthly: document.getElementById('panel-monthly')
        };
        const summaries = {
            daily: document.querySelector('[data-summary="daily"]'),
            weekly: document.querySelector('[data-summary="weekly"]'),
            monthly: document.querySelector('[data-summary="monthly"]')
        };

        const dailyData = @json($chart['days']);
        const weeklyData = @json($weekly);
        const monthlyData = @json($monthly);

        function makeBarChart(canvas, labels, vData, cData) {
            return new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: @json(__('Pengunjung')),
                        data: vData,
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
                        data: cData,
                        backgroundColor: '#282829',
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
                                title: (t) => labels[t[0].dataIndex],
                                label: (c) => ' ' + c.dataset.label + ': ' + nf.format(c.raw),
                            }
                        })
                    },
                    scales: { x: scX, y: scY }
                }
            });
        }

        const charts = {
            daily: null,
            weekly: null,
            monthly: null
        };

        function initDaily() {
            if (charts.daily || !dailyData.length) return;
            charts.daily = new Chart(document.getElementById('chartDaily'), {
                type: 'line',
                data: {
                    labels: dailyData.map(d => d.label),
                    datasets: [{
                        label: @json(__('Pengunjung')),
                        data: dailyData.map(d => d.visitors),
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
                                title: (t) => fmtDate(dailyData[t[0].dataIndex].date),
                                label: (c) => ' ' + @json(__('Pengunjung')) + ': ' + nf.format(c.raw),
                            }
                        })
                    },
                    scales: { x: scX, y: scY }
                }
            });
        }

        function initWeekly() {
            if (charts.weekly) return;
            charts.weekly = makeBarChart(
                document.getElementById('chartWeekly'),
                weeklyData.map(w => w.label.split(' – ')[0]),
                weeklyData.map(w => w.visitors),
                weeklyData.map(w => w.clicks)
            );
        }

        function initMonthly() {
            if (charts.monthly) return;
            charts.monthly = makeBarChart(
                document.getElementById('chartMonthly'),
                monthlyData.map(m => m.label),
                monthlyData.map(m => m.visitors),
                monthlyData.map(m => m.clicks)
            );
        }

        const init = { daily: initDaily, weekly: initWeekly, monthly: initMonthly };

        function activate(tab) {
            Object.keys(panels).forEach(function (key) {
                panels[key].style.display = key === tab ? '' : 'none';
                summaries[key].classList.toggle('hidden', key !== tab);
            });
            tabBtns.forEach(function (btn) {
                var isActive = btn.dataset.chartTab === tab;
                btn.className = 'tab-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all ' +
                    (isActive ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-700');
            });
            init[tab]();
        }

        if (tabBtns.length) {
            tabBtns.forEach(function (btn) {
                btn.addEventListener('click', function () { activate(btn.dataset.chartTab); });
            });
            activate('daily');
        }
    </script>
@endsection