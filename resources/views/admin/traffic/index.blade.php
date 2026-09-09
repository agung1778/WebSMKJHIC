@extends('layouts.admin-app')

@section('title', 'Lalu Lintas Website')

@push('styles')
    <style>
        input[type='search']::-webkit-search-decoration,
        input[type='search']::-webkit-search-cancel-button,
        input[type='search']::-webkit-search-results-button,
        input[type='search']::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
    @php
        $avg = $cards['avg'] ?? $cards;
        $fmt = fn ($n) => number_format((float) $n, 0, ',', '.');
        $pct = fn ($part, $total) => $total > 0 ? number_format(((float) $part / (float) $total) * 100, 1, ',', '.') : '0,0';
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- HEADER --}}
        <header
            class="flex flex-col lg:flex-row justify-between items-start lg:items-center pb-4 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-[#6CF600]"></i>
                    Lalu Lintas Website
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    Pantau pengunjung, klik link, dan klik button pada website SMK Amaliah.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                <form method="GET" action="{{ route('admin.traffic.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">

                    <div class="relative flex-1 sm:w-52">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-regular fa-calendar text-slate-400"></i>
                        </span>
                        <select name="period" id="periodSelect"
                            class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                            @foreach (\App\Services\TrafficService::PERIODS as $key => $label)
                                <option value="{{ $key }}" @selected($period['period'] === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="customDateFields"
                        class="hidden flex-col sm:flex-row gap-2 items-center @if ($period['period'] === 'custom') !flex @endif">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                        <span class="text-slate-400 text-xs">–</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6CF600]">
                    </div>

                    <div class="relative flex-1 sm:w-48">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </span>
                        <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Cari link / button / URL..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#6CF600] transition-shadow">
                    </div>

                    <button type="submit"
                        class="bg-[#1e1e1e] text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </form>

                <a href="{{ route('admin.traffic.export', request()->query()) }}"
                    class="bg-[#6CF600] text-black px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-csv"></i> Ekspor CSV
                </a>
            </div>
        </header>

        @php
            $message = null;
            if (\Illuminate\Support\Facades\Session::has('error')) {
                $message = session('error');
            }
        @endphp
        @if ($message)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm font-medium">{{ $message }}</p>
            </div>
        @endif

        {{-- SUMMARY CARDS --}}
        <section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                @php
                    $summaryCards = [
                        ['icon' => 'fa-users', 'label' => 'Total Pengunjung', 'value' => $fmt($avg['total_visitors']), 'raw' => (int) $avg['total_visitors'], 'accent' => 'text-[#6CF600] bg-[#6CF600]/10'],
                        ['icon' => 'fa-link', 'label' => 'Total Klik Link', 'value' => $fmt($avg['total_link_clicks']), 'raw' => (int) $avg['total_link_clicks'], 'accent' => 'text-blue-500 bg-blue-50'],
                        ['icon' => 'fa-arrow-pointer', 'label' => 'Total Klik Button', 'value' => $fmt($avg['total_button_clicks']), 'raw' => (int) $avg['total_button_clicks'], 'accent' => 'text-purple-500 bg-purple-50'],
                        ['icon' => 'fa-calendar-day', 'label' => 'Pengunjung Hari Ini', 'value' => $fmt($avg['today_visitors']), 'raw' => (int) $avg['today_visitors'], 'accent' => 'text-emerald-500 bg-emerald-50'],
                        ['icon' => 'fa-calendar-week', 'label' => 'Pengunjung 7 Hari', 'value' => $fmt($avg['week_visitors']), 'raw' => (int) $avg['week_visitors'], 'accent' => 'text-amber-500 bg-amber-50'],
                        ['icon' => 'fa-calendar-days', 'label' => 'Pengunjung 30 Hari', 'value' => $fmt($avg['month_visitors']), 'raw' => (int) $avg['month_visitors'], 'accent' => 'text-rose-500 bg-rose-50'],
                    ];
                @endphp

                @foreach ($summaryCards as $card)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="rounded-lg h-11 w-11 flex items-center justify-center shrink-0 {{ $card['accent'] }}">
                            <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">{{ $card['label'] }}</p>
                            <p class="text-xl font-bold text-slate-800 leading-tight" data-count="{{ $card['raw'] }}">{{ $card['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- STATISTIK TRAFFIC --}}
        <section class="space-y-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-[#6CF600]"></i>
                <h2 class="text-lg font-bold text-slate-800">Statistik Lalu Lintas</h2>
                <span class="ml-auto text-xs text-slate-400 font-medium">{{ $period['label'] }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- Grafik Pengunjung per Hari --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700">Pengunjung per Hari</h3>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pengunjung Unik</span>
                    </div>
                    <div class="p-5">
                        @if ($daily['days']->isEmpty())
                            <p class="text-sm text-slate-400 text-center py-8">
                                Grafik harian hanya tersedia untuk periode tertentu (bukan "Semua Waktu").
                            </p>
                        @else
                            <div class="relative w-full" style="height: 250px;">
                                <canvas id="chartDailyVisitors"></canvas>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-xs">
                                <span class="text-slate-500 font-medium">Total hari: <b>{{ $daily['days']->count() }}</b></span>
                                <span class="text-[#6CF600] font-bold">{{ $fmt($daily['days']->sum('visitors')) }} kunjungan</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Grafik Klik per Hari --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700">Klik per Hari</h3>
                        <div class="flex items-center gap-3 text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-blue-400"></span> Link</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-purple-400"></span> Button</span>
                        </div>
                    </div>
                    <div class="p-5">
                        @if ($daily['days']->isEmpty())
                            <p class="text-sm text-slate-400 text-center py-8">
                                Data klik belum tersedia untuk periode ini.
                            </p>
                        @else
                            <div class="relative w-full" style="height: 250px;">
                                <canvas id="chartDailyClicks"></canvas>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-xs">
                                <span class="text-slate-500 font-medium">Total klik: <b>{{ $fmt($daily['days']->sum('clicks')) }}</b></span>
                                <span class="text-blue-500 font-bold">{{ $fmt($daily['days']->sum('links')) }} link</span>
                                <span class="text-purple-500 font-bold">{{ $fmt($daily['days']->sum('buttons')) }} button</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Statistik Mingguan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700">Statistik Mingguan</h3>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">8 Minggu Terakhir</span>
                    </div>
                    <div class="p-5">
                        <div class="relative w-full" style="height: 220px;">
                            <canvas id="chartWeekly"></canvas>
                        </div>
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-slate-100 text-[10px] font-semibold uppercase tracking-wider">
                            <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-[#6CF600]/70"></span> Pengunjung</span>
                            <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-[#1e1e1e]"></span> Klik</span>
                            <span class="ml-auto text-slate-500">{{ $fmt($weekly->sum('visitors')) }} pengunjung</span>
                        </div>
                    </div>
                </div>

                {{-- Statistik Bulanan --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700">Statistik Bulanan</h3>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">12 Bulan Terakhir</span>
                    </div>
                    <div class="p-5">
                        <div class="relative w-full" style="height: 220px;">
                            <canvas id="chartMonthly"></canvas>
                        </div>
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-slate-100 text-[10px] font-semibold uppercase tracking-wider">
                            <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-[#6CF600]/70"></span> Pengunjung</span>
                            <span class="flex items-center gap-1 text-slate-400"><span class="w-2 h-2 rounded-sm bg-[#1e1e1e]"></span> Klik</span>
                            <span class="ml-auto text-slate-500">{{ $fmt($monthly->sum('visitors')) }} pengunjung</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- TRAFFIC SETIAP LINK --}}
        <section>
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-solid fa-link text-[#6CF600]"></i>
                <h2 class="text-lg font-bold text-slate-800">Lalu Lintas Setiap Link</h2>
                <span class="ml-auto text-xs text-slate-400 font-medium">{{ $fmt($totalLinks) }} total klik</span>
            </div>

            <div class="md:hidden space-y-3">
                @forelse($links['rows'] as $row)
                    @php
                        $p = $row->clicks > 0 && $totalLinks > 1 ? ((float) $row->clicks / (float) $totalLinks) * 100 : 0;
                    @endphp

                    {{-- Kartu responsif (mobile) --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ $row->element_url ?: '#' }}" target="_blank" rel="noopener noreferrer"
                                class="font-semibold text-slate-800 break-words leading-snug">{{ $row->element_name }}</a>
                            <span class="shrink-0 bg-[#6CF600]/10 text-[#1e1e1e] font-bold text-sm px-2.5 py-1 rounded-lg">{{ $fmt($row->clicks) }}</span>
                        </div>
                        <p class="text-xs text-[#6CF600] break-all leading-snug">{{ $row->element_url ?: '-' }}</p>
                        <div class="grid grid-cols-3 gap-2 text-center border-t border-slate-100 pt-3">
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $fmt($row->clicks) }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Klik</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $fmt($row->unique_visitors) }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Pengunjung</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-600">
                                    {{ $row->last_click ? \Carbon\Carbon::parse($row->last_click)->format('d M Y') : '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Terakhir</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#6CF600] rounded-full" style="width: {{ min(100, $p) }}%;"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">{{ number_format($p, 1, ',', '.') }}%</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm py-10 text-center text-slate-400 text-sm">
                        <i class="fa-solid fa-link-slash mb-2 block text-2xl"></i>
                        Belum ada data klik link pada periode ini.
                    </div>
                @endforelse
            </div>

            <div class="hidden md:block bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                                <th class="py-4 px-6 w-14 text-center">No</th>
                                <th class="py-4 px-6">Nama Link</th>
                                <th class="py-4 px-6">URL</th>
                                <th class="py-4 px-6 text-center">Jumlah Klik</th>
                                <th class="py-4 px-6 text-center">Pengunjung Unik</th>
                                <th class="py-4 px-6">Klik Terakhir</th>
                                <th class="py-4 px-6 w-40">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                            @foreach($links['rows'] as $row)
                                @php
                                    $p = $row->clicks > 0 && $totalLinks > 1 ? ((float) $row->clicks / (float) $totalLinks) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-6 font-semibold text-slate-800 max-w-[220px]">
                                        <span class="block truncate" title="{{ $row->element_name }}">{{ $row->element_name }}</span>
                                    </td>
                                    <td class="py-4 px-6 max-w-[260px]">
                                        <a href="{{ $row->element_url ?: '#' }}" target="_blank" rel="noopener noreferrer"
                                            class="text-[#6CF600] hover:underline truncate block text-xs"
                                            title="{{ $row->element_url ?: '-' }}">{{ $row->element_url ?: '-' }}</a>
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold text-slate-800">{{ $fmt($row->clicks) }}</td>
                                    <td class="py-4 px-6 text-center">{{ $fmt($row->unique_visitors) }}</td>
                                    <td class="py-4 px-6 text-xs whitespace-nowrap">
                                        {{ $row->last_click ? \Carbon\Carbon::parse($row->last_click)->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-[#6CF600] rounded-full" style="width: {{ min(100, $p) }}%;"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-500 w-12 text-right">{{ number_format($p, 1, ',', '.') }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- TRAFFIC SETIAP BUTTON --}}
        <section>
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-solid fa-arrow-pointer text-[#6CF600]"></i>
                <h2 class="text-lg font-bold text-slate-800">Lalu Lintas Setiap Button</h2>
                <span class="ml-auto text-xs text-slate-400 font-medium">{{ $fmt($totalButtons) }} total klik</span>
            </div>

            <div class="md:hidden space-y-3">
                @forelse($buttons['rows'] as $row)
                    @php
                        $p = $row->clicks > 0 && $totalButtons > 1 ? ((float) $row->clicks / (float) $totalButtons) * 100 : 0;
                    @endphp

                    {{-- Kartu responsif (mobile) --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="font-semibold text-slate-800 break-words leading-snug">{{ $row->element_name }}</h4>
                            <span class="shrink-0 bg-purple-50 text-purple-700 font-bold text-sm px-2.5 py-1 rounded-lg">{{ $fmt($row->clicks) }}</span>
                        </div>
                        <p class="text-xs text-slate-500 break-all leading-snug">{{ $row->page_url ?: '-' }}</p>
                        <div class="grid grid-cols-3 gap-2 text-center border-t border-slate-100 pt-3">
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $fmt($row->clicks) }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Klik</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $fmt($row->unique_visitors) }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Pengunjung</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-600">
                                    {{ $row->last_click ? \Carbon\Carbon::parse($row->last_click)->format('d M Y') : '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Terakhir</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-400 rounded-full" style="width: {{ min(100, $p) }}%;"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">{{ number_format($p, 1, ',', '.') }}%</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm py-10 text-center text-slate-400 text-sm">
                        <i class="fa-solid fa-arrow-pointer-slash mb-2 block text-2xl"></i>
                        Belum ada data klik button pada periode ini.
                    </div>
                @endforelse
            </div>

            <div class="hidden md:block bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                                <th class="py-4 px-6 w-14 text-center">No</th>
                                <th class="py-4 px-6">Nama Button</th>
                                <th class="py-4 px-6">Lokasi Halaman</th>
                                <th class="py-4 px-6 text-center">Jumlah Klik</th>
                                <th class="py-4 px-6 text-center">Pengunjung Unik</th>
                                <th class="py-4 px-6">Klik Terakhir</th>
                                <th class="py-4 px-6 w-40">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                            @foreach($buttons['rows'] as $row)
                                @php
                                    $p = $row->clicks > 0 && $totalButtons > 1 ? ((float) $row->clicks / (float) $totalButtons) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-6 font-semibold text-slate-800 max-w-[220px]">
                                        <span class="block truncate" title="{{ $row->element_name }}">{{ $row->element_name }}</span>
                                    </td>
                                    <td class="py-4 px-6 max-w-[260px]">
                                        <span class="block truncate text-xs" title="{{ $row->page_url ?: '-' }}">{{ $row->page_url ?: '-' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold text-slate-800">{{ $fmt($row->clicks) }}</td>
                                    <td class="py-4 px-6 text-center">{{ $fmt($row->unique_visitors) }}</td>
                                    <td class="py-4 px-6 text-xs whitespace-nowrap">
                                        {{ $row->last_click ? \Carbon\Carbon::parse($row->last_click)->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-purple-400 rounded-full" style="width: {{ min(100, $p) }}%;"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-500 w-12 text-right">{{ number_format($p, 1, ',', '.') }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- DETAIL PENGUNJUNG --}}
        <section>
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-solid fa-user-clock text-[#6CF600]"></i>
                <h2 class="text-lg font-bold text-slate-800">Detail Pengunjung</h2>
                <span class="ml-auto text-xs text-slate-400 font-medium">{{ $fmt($visitors->total()) }} data</span>
            </div>

            <div class="space-y-3 md:hidden">
                @forelse($visitors as $visitor)
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-3 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">
                                <i class="fa-solid fa-clock mr-1 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($visitor->visited_at)->format('d M Y H:i:s') }}
                            </span>
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-semibold uppercase shrink-0">
                                {{ $visitor->country ?: '-' }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Halaman Asal</p>
                                <p class="text-xs text-slate-600 break-all leading-snug">{{ $visitor->referrer ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Halaman Tujuan</p>
                                <a href="{{ $visitor->page_url }}" target="_blank" rel="noopener noreferrer"
                                    class="text-[#6CF600] hover:underline text-xs break-all leading-snug">{{ $visitor->page_url }}</a>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100 text-xs">
                            <span class="px-2 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-medium">
                                {{ $visitor->browser ?? '-' }}
                            </span>
                            <span class="px-2 py-1 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 text-[11px] font-medium">
                                {{ $visitor->device ?? '-' }}
                            </span>
                            <span class="px-2 py-1 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 text-[11px] font-medium">
                                {{ $visitor->os ?? '-' }}
                            </span>
                            <span class="px-2 py-1 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 font-mono text-[11px]">
                                {{ $visitor->ip_address ?: '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm py-10 text-center text-slate-400 text-sm">
                        <i class="fa-solid fa-user-slash mb-2 block text-2xl"></i>
                        Belum ada data pengunjung pada periode ini.
                    </div>
                @endforelse

                @if ($visitors->hasPages())
                    <div class="py-4">
                        {{ $visitors->links() }}
                    </div>
                @endif
            </div>

            <div class="hidden md:block bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                                <th class="py-4 px-6">Waktu Akses</th>
                                <th class="py-4 px-6">Halaman Asal</th>
                                <th class="py-4 px-6">Halaman Tujuan</th>
                                <th class="py-4 px-6">Browser</th>
                                <th class="py-4 px-6">Perangkat</th>
                                <th class="py-4 px-6">Sistem Operasi</th>
                                <th class="py-4 px-6 text-center">Negara</th>
                                <th class="py-4 px-6">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                            @forelse($visitors as $visitor)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-6 text-xs whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($visitor->visited_at)->format('d M Y H:i:s') }}
                                    </td>
                                    <td class="py-4 px-6 max-w-[200px]">
                                        <span class="block truncate text-xs" title="{{ $visitor->referrer ?: '-' }}">
                                            {{ $visitor->referrer ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 max-w-[220px]">
                                        <a href="{{ $visitor->page_url }}" target="_blank" rel="noopener noreferrer"
                                            class="text-[#6CF600] hover:underline truncate block text-xs"
                                            title="{{ $visitor->page_url }}">{{ $visitor->page_url }}</a>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 text-xs font-medium">
                                            {{ $visitor->browser ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">{{ $visitor->device ?? '-' }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap">{{ $visitor->os ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold uppercase">
                                            {{ $visitor->country ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-xs font-mono">{{ $visitor->ip_address ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-10 text-center text-slate-400 text-sm">
                                        <i class="fa-solid fa-user-slash mb-2 block text-2xl"></i>
                                        Belum ada data pengunjung pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($visitors->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $visitors->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var periodSelect = document.getElementById('periodSelect');
            var customFields = document.getElementById('customDateFields');

            function syncCustomFields() {
                if (!periodSelect || !customFields) return;
                var isCustom = periodSelect.value === 'custom';
                customFields.classList.toggle('hidden', !isCustom);
                customFields.classList.toggle('flex', isCustom);
            }

            if (periodSelect) {
                periodSelect.addEventListener('change', syncCustomFields);
                syncCustomFields();
            }

            // ============ Chart.js : Traffic lebih hidup ============
            var nf = new Intl.NumberFormat('id-ID');

            function fmtDate(dateStr) {
                return new Date(dateStr + 'T00:00:00')
                    .toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }

            // Animasi angka (count-up) pada kartu ringkasan
            document.querySelectorAll('[data-count]').forEach(function (el) {
                var target = parseFloat(el.dataset.count || '0') || 0;
                var duration = 1100;
                var start = performance.now();
                (function frame(now) {
                    var p = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = nf.format(Math.round(target * eased));
                    if (p < 1) requestAnimationFrame(frame);
                })(performance.now());
            });

            function makeGradient(ctx, area, from, to) {
                if (!area) return from;
                var g = ctx.createLinearGradient(0, area.bottom, 0, area.top);
                g.addColorStop(0, to);
                g.addColorStop(1, from);
                return g;
            }

            var tooltipBase = {
                backgroundColor: '#1e1e1e',
                titleColor: '#ffffff',
                bodyColor: '#e2e8f0',
                padding: 12,
                cornerRadius: 10,
                boxPadding: 6,
                usePointStyle: true,
            };

            var scX = {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#94a3b8', maxRotation: 0, autoSkipPadding: 8, font: { size: 10 } }
            };
            var scY = {
                beginAtZero: true,
                border: { display: false },
                grid: { color: 'rgba(226,232,240,.6)' },
                ticks: { color: '#94a3b8', precision: 0, font: { size: 10 } }
            };

            // 1. Pengunjung per Hari
            var chartDailyVisitors = document.getElementById('chartDailyVisitors');
            if (chartDailyVisitors) {
                var daily = @json($daily['days']->values());
                new Chart(chartDailyVisitors, {
                    type: 'bar',
                    data: {
                        labels: daily.map(function (d) { return d.label; }),
                        datasets: [{
                            label: 'Pengunjung',
                            data: daily.map(function (d) { return d.visitors; }),
                            backgroundColor: function (ctx) {
                                return makeGradient(ctx.chart.ctx, ctx.chartArea, '#63cd00', 'rgba(99,205,0,0.12)');
                            },
                            hoverBackgroundColor: '#5bd300',
                            borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 2, bottomRight: 2 },
                            borderSkipped: false,
                            maxBarThickness: 26,
                            animation: { duration: 1200, easing: 'easeOutQuart' }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: Object.assign({}, tooltipBase, {
                                callbacks: {
                                    title: function (t) { return fmtDate(daily[t[0].dataIndex].date); },
                                    label: function (c) { return ' Pengunjung: ' + nf.format(c.raw); },
                                    footer: function (c) { return ' Page views: ' + nf.format(daily[c[0].dataIndex].views); }
                                }
                            })
                        },
                        scales: { x: scX, y: scY }
                    }
                });
            }

            // 2. Klik per Hari (stacked = link + button)
            var chartDailyClicks = document.getElementById('chartDailyClicks');
            if (chartDailyClicks) {
                var dailyClk = @json($daily['days']->values());
                new Chart(chartDailyClicks, {
                    type: 'bar',
                    data: {
                        labels: dailyClk.map(function (d) { return d.label; }),
                        datasets: [{
                            label: 'Link',
                            data: dailyClk.map(function (d) { return d.links; }),
                            backgroundColor: '#3b82f6',
                            hoverBackgroundColor: '#2563eb',
                            stack: 'klik',
                            maxBarThickness: 22,
                            borderRadius: 4,
                            borderSkipped: false
                        }, {
                            label: 'Button',
                            data: dailyClk.map(function (d) { return d.buttons; }),
                            backgroundColor: '#a855f7',
                            hoverBackgroundColor: '#9333ea',
                            stack: 'klik',
                            maxBarThickness: 22,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: Object.assign({}, tooltipBase, {
                                callbacks: {
                                    title: function (t) { return fmtDate(dailyClk[t[0].dataIndex].date); },
                                    label: function (c) { return ' ' + c.dataset.label + ': ' + nf.format(c.raw); },
                                    footer: function (c) {
                                        var d = dailyClk[c[0].dataIndex];
                                        return ' Total: ' + nf.format(d.clicks) + ' klik';
                                    }
                                }
                            })
                        },
                        scales: {
                            x: Object.assign({ stacked: true }, scX),
                            y: Object.assign({ stacked: true }, scY)
                        }
                    }
                });
            }

            // 3. Statistik Mingguan
            var chartWeekly = document.getElementById('chartWeekly');
            if (chartWeekly) {
                var weekly = @json($weekly);
                new Chart(chartWeekly, {
                    type: 'bar',
                    data: {
                        labels: weekly.map(function (w) { return w.label.split(' – ')[0]; }),
                        datasets: [{
                            label: 'Pengunjung',
                            data: weekly.map(function (w) { return w.visitors; }),
                            backgroundColor: function (ctx) {
                                return makeGradient(ctx.chart.ctx, ctx.chartArea, '#6CF600', 'rgba(108,246,0,0.15)');
                            },
                            hoverBackgroundColor: '#5bd300',
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 22
                        }, {
                            label: 'Klik',
                            data: weekly.map(function (w) { return w.clicks; }),
                            backgroundColor: '#1e1e1e',
                            hoverBackgroundColor: '#3f3f46',
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 22
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: Object.assign({}, tooltipBase, {
                                callbacks: {
                                    title: function (t) { return weekly[t[0].dataIndex].label; },
                                    label: function (c) { return ' ' + c.dataset.label + ': ' + nf.format(c.raw); }
                                }
                            })
                        },
                        scales: { x: scX, y: scY }
                    }
                });
            }

            // 4. Statistik Bulanan
            var chartMonthly = document.getElementById('chartMonthly');
            if (chartMonthly) {
                var monthly = @json($monthly);
                new Chart(chartMonthly, {
                    type: 'bar',
                    data: {
                        labels: monthly.map(function (m) { return m.label; }),
                        datasets: [{
                            label: 'Pengunjung',
                            data: monthly.map(function (m) { return m.visitors; }),
                            backgroundColor: function (ctx) {
                                return makeGradient(ctx.chart.ctx, ctx.chartArea, '#6CF600', 'rgba(108,246,0,0.15)');
                            },
                            hoverBackgroundColor: '#5bd300',
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 22
                        }, {
                            label: 'Klik',
                            data: monthly.map(function (m) { return m.clicks; }),
                            backgroundColor: '#1e1e1e',
                            hoverBackgroundColor: '#3f3f46',
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 22
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: Object.assign({}, tooltipBase, {
                                callbacks: {
                                    title: function (t) { return monthly[t[0].dataIndex].label; },
                                    label: function (c) { return ' ' + c.dataset.label + ': ' + nf.format(c.raw); }
                                }
                            })
                        },
                        scales: { x: scX, y: scY }
                    }
                });
            }
        });
    </script>
@endsection