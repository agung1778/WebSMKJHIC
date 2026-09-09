<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TrafficVisitor;
use App\Models\TrafficClick;

class TrafficService
{
    /**
     * Periode filter yang didukung.
     */
    public const PERIODS = [
        'today'      => 'Hari Ini',
        'yesterday'  => 'Kemarin',
        '7d'         => '7 Hari Terakhir',
        '30d'        => '30 Hari Terakhir',
        'month'      => 'Bulan Ini',
        'custom'     => 'Custom Date Range',
        'all'        => 'Semua Waktu',
    ];

    /**
     * Parse string User-Agent menjadi browser, device, dan OS.
     * Tidak menggunakan library pihak ketiga.
     */
    public function parseUserAgent(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return ['browser' => 'Unknown', 'device' => 'Unknown', 'os' => 'Unknown'];
        }

        $ua = strtolower($userAgent);

        // Browser
        if (str_contains($ua, 'edg/')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($ua, 'opr/') || str_contains($ua, 'opera')) {
            $browser = 'Opera';
        } elseif (str_contains($ua, 'chrome')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'samsungbrowser')) {
            $browser = 'Samsung Internet';
        } elseif (str_contains($ua, 'safari')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'msie') || str_contains($ua, 'trident')) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/\b(bot|spider|crawler|curl|python-requests|wget|postman)\b/', $ua)) {
            $browser = 'Bot / Crawler';
        } else {
            $browser = 'Other';
        }

        // Device
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            $device = 'Tablet';
        } elseif (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            $device = 'Mobile';
        } else {
            $device = 'Desktop';
        }

        // OS
        if (str_contains($ua, 'windows')) {
            $os = 'Windows';
        } elseif (str_contains($ua, 'android')) {
            $os = 'Android';
        } elseif (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) {
            $os = 'iOS';
        } elseif (str_contains($ua, 'mac os x') || str_contains($ua, 'macintosh')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'linux')) {
            $os = 'Linux';
        } else {
            $os = 'Other';
        }

        return ['browser' => $browser, 'device' => $device, 'os' => $os];
    }

    /**
     * Ambil negara secara best-effort dari header CDN/proxy yang sudah tersedia.
     * Tidak menambahkan layanan pihak ketiga.
     */
    public function countryFromRequest(Request $request): ?string
    {
        foreach (['CF-IPCountry', 'X-AppEngine-Country', 'X-Country-Code', 'X-Verify-Country'] as $header) {
            if ($country = $request->header($header)) {
                return substr($country, 0, 2);
            }
        }

        return null;
    }

    /**
     * Siapkan rentang tanggal berdasarkan periode terpilih.
     */
    public function resolvePeriod(Request $request): array
    {
        $period = $request->input('period', '30d');

        if (! array_key_exists($period, self::PERIODS)) {
            $period = '30d';
        }

        $start = null;
        $end = null;

        switch ($period) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::tomorrow()->subSecond();
                break;
            case 'yesterday':
                $start = Carbon::yesterday();
                $end = Carbon::today()->subSecond();
                break;
            case '7d':
                $start = Carbon::today()->subDays(6);
                $end = Carbon::tomorrow()->subSecond();
                break;
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                if (! $this->validDate($startDate) || ! $this->validDate($endDate)) {
                    $period = '30d';
                    $start = Carbon::today()->subDays(29);
                    $end = Carbon::tomorrow()->subSecond();
                } else {
                    $start = Carbon::parse($startDate)->startOfDay();
                    $end = Carbon::parse($endDate)->endOfDay();
                }
                break;
            case '30d':
                $start = Carbon::today()->subDays(29);
                $end = Carbon::tomorrow()->subSecond();
                break;
            default: // 'all'
                $period = 'all';
        }

        return [
            'period' => $period,
            'label'  => self::PERIODS[$period] ?? 'Semua Waktu',
            'start'  => $start,
            'end'    => $end,
        ];
    }

    /**
     * Data untuk seluruh kartu ringkasan dashboard.
     */
    public function summaryCards(): array
    {
        $today = Carbon::today()->toDateString();
        $weekStart = Carbon::today()->subDays(6)->toDateString();
        $monthStart = Carbon::today()->subDays(29)->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        $visitorsToday = $this->countUniqueVisitorsBetween($today, $tomorrow);
        $visitorsWeek = $this->countUniqueVisitorsBetween($weekStart, $tomorrow);
        $visitorsMonth = $this->countUniqueVisitorsBetween($monthStart, $tomorrow);

        return [
            'avg' => [
                'total_visitors'        => TrafficVisitor::query()->count('id'),
                'total_link_clicks'     => TrafficClick::query()->where('click_type', 'link')->count(),
                'total_button_clicks'   => TrafficClick::query()->where('click_type', 'button')->count(),
                'today_visitors'        => $visitorsToday,
                'week_visitors'         => $visitorsWeek,
                'month_visitors'        => $visitorsMonth,
                'today_pages'           => TrafficVisitor::query()
                    ->whereBetween('visited_at', [$today, $tomorrow])->count(),
            ],
        ];
    }

    /**
     * Statistik grafik per hari (menghormati periode terpilih).
     */
    public function dailyStats(array $period): array
    {
        $visitorQuery = TrafficVisitor::query()
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as views'))
            ->when($period['start'], fn ($q) => $q->whereBetween('visited_at', [$period['start'], $period['end']]))
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->orderBy('date');

        $uniqueQuery = TrafficVisitor::query()
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(DISTINCT visitor_token) as visitors'))
            ->when($period['start'], fn ($q) => $q->whereBetween('visited_at', [$period['start'], $period['end']]))
            ->groupBy(DB::raw('DATE(visited_at)'));

        $clickQuery = TrafficClick::query()
            ->select(
                DB::raw('DATE(clicked_at) as date'),
                DB::raw('COUNT(*) as clicks'),
                DB::raw("SUM(click_type = 'link') as links"),
                DB::raw("SUM(click_type = 'button') as buttons")
            )
            ->when($period['start'], fn ($q) => $q->whereBetween('clicked_at', [$period['start'], $period['end']]))
            ->groupBy(DB::raw('DATE(clicked_at)'))
            ->orderBy('date');

        $visitors = $uniqueQuery->pluck('visitors', 'date');
        $views = $visitorQuery->pluck('views', 'date');
        $clicks = $clickQuery->get()->keyBy('date');

        $days = $this->dayRange($period);

        $daily = collect($days)->map(function (Carbon $day) use ($visitors, $views, $clicks) {
            $key = $day->format('Y-m-d');

            $click = $clicks->get($key);

            return [
                'date'    => $day->format('Y-m-d'),
                'label'   => $day->format('d M'),
                'visitors' => (int) ($visitors[$key] ?? 0),
                'views'    => (int) ($views[$key] ?? 0),
                'clicks'   => (int) ($click->clicks ?? 0),
                'links'    => (int) ($click->links ?? 0),
                'buttons'  => (int) ($click->buttons ?? 0),
            ];
        });

        return ['days' => $daily, 'max_visitors' => max(1, $daily->max('visitors')), 'max_clicks' => max(1, $daily->max('clicks'))];
    }

    /**
     * Statistik mingguan (8 minggu terakhir).
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function weeklyStats()
    {
        $start = Carbon::today()->subWeeks(7)->startOfWeek();
        $end = Carbon::today()->endOfWeek();

        $visitors = TrafficVisitor::query()
            ->select(DB::raw('YEARWEEK(visited_at, 1) as week'), DB::raw('COUNT(DISTINCT visitor_token) as visitors'), DB::raw('COUNT(*) as views'))
            ->whereBetween('visited_at', [$start, $end])
            ->groupBy('week')->pluck('visitors', 'week');

        $clicks = TrafficClick::query()
            ->select(DB::raw('YEARWEEK(clicked_at, 1) as week'), DB::raw('COUNT(*) as clicks'))
            ->whereBetween('clicked_at', [$start, $end])
            ->groupBy('week')->pluck('clicks', 'week');

        return collect(range(0, 7))->map(function ($i) use ($start, $visitors, $clicks) {
            $weekStart = $start->copy()->addWeeks($i);
            $weekKey = $weekStart->format('oW');

            return [
                'label'    => $weekStart->format('d M') . ' – ' . $weekStart->copy()->endOfWeek()->format('d M'),
                'visitors' => (int) ($visitors[$weekKey] ?? 0),
                'clicks'   => (int) ($clicks[$weekKey] ?? 0),
            ];
        });
    }

    /**
     * Statistik bulanan (12 bulan terakhir).
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function monthlyStats()
    {
        $start = Carbon::today()->subMonths(11)->startOfMonth();
        $end = Carbon::today()->endOfMonth();

        $visitors = TrafficVisitor::query()
            ->select(DB::raw('DATE_FORMAT(visited_at, "%Y-%m") as month'), DB::raw('COUNT(DISTINCT visitor_token) as visitors'), DB::raw('COUNT(*) as views'))
            ->whereBetween('visited_at', [$start, $end])
            ->groupBy('month')->pluck('visitors', 'month');

        $clicks = TrafficClick::query()
            ->select(DB::raw('DATE_FORMAT(clicked_at, "%Y-%m") as month'), DB::raw('COUNT(*) as clicks'))
            ->whereBetween('clicked_at', [$start, $end])
            ->groupBy('month')->pluck('clicks', 'month');

        return collect(range(0, 11))->map(function ($i) use ($start, $visitors, $clicks) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');

            return [
                'label'    => $month->translatedFormat('M Y'),
                'visitors' => (int) ($visitors[$key] ?? 0),
                'clicks'   => (int) ($clicks[$key] ?? 0),
            ];
        });
    }

    /**
     * Trafik per link (menghormati periode & pencarian).
     */
    public function linkTraffic(array $period, ?string $search = null): array
    {
        $query = TrafficClick::query()
            ->select(
                'element_name',
                DB::raw('MAX(element_url) as element_url'),
                DB::raw('COUNT(*) as clicks'),
                DB::raw('COUNT(DISTINCT visitor_token) as unique_visitors'),
                DB::raw('MAX(clicked_at) as last_click')
            )
            ->where('click_type', 'link')
            ->when($period['start'], fn ($q) => $q->whereBetween('clicked_at', [$period['start'], $period['end']]))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('element_name', 'like', "%{$search}%")
                        ->orWhere('element_url', 'like', "%{$search}%")
                        ->orWhere('page_url', 'like', "%{$search}%");
                });
            })
            ->groupBy('element_name')
            ->orderByDesc('clicks');

        $total = (clone $query)->get()->sum('clicks');

        return [
            'rows'  => $query->get(),
            'total' => max(1, $total),
        ];
    }

    /**
     * Trafik per button (menghormati periode & pencarian).
     */
    public function buttonTraffic(array $period, ?string $search = null): array
    {
        $query = TrafficClick::query()
            ->select(
                'element_name',
                DB::raw('MAX(page_url) as page_url'),
                DB::raw('COUNT(*) as clicks'),
                DB::raw('COUNT(DISTINCT visitor_token) as unique_visitors'),
                DB::raw('MAX(clicked_at) as last_click')
            )
            ->where('click_type', 'button')
            ->when($period['start'], fn ($q) => $q->whereBetween('clicked_at', [$period['start'], $period['end']]))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('element_name', 'like', "%{$search}%")
                        ->orWhere('page_url', 'like', "%{$search}%");
                });
            })
            ->groupBy('element_name')
            ->orderByDesc('clicks');

        $total = (clone $query)->get()->sum('clicks');

        return [
            'rows'  => $query->get(),
            'total' => max(1, $total),
        ];
    }

    /**
     * Detail pengunjung (menghormati periode & pencarian).
     */
    public function visitorDetails(array $period, ?string $search = null)
    {
        return TrafficVisitor::query()
            ->when($period['start'], fn ($q) => $q->whereBetween('visited_at', [$period['start'], $period['end']]))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('page_url', 'like', "%{$search}%")
                        ->orWhere('referrer', 'like', "%{$search}%")
                        ->orWhere('browser', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('visited_at')
            ->paginate(50)
            ->withQueryString();
    }

    /**
     * Data mentah klik untuk export CSV.
     */
    public function exportData(array $period, ?string $search = null): array
    {
        $linkRows = collect($this->linkTraffic($period, $search)['rows'])->map(function ($row) {
            return [
                $row->element_name,
                $row->element_url,
                $row->clicks,
                $row->unique_visitors,
                Carbon::parse($row->last_click)->format('Y-m-d H:i:s'),
            ];
        });

        $buttonRows = collect($this->buttonTraffic($period, $search)['rows'])->map(function ($row) {
            return [
                $row->element_name,
                $row->page_url,
                $row->clicks,
                $row->unique_visitors,
                Carbon::parse($row->last_click)->format('Y-m-d H:i:s'),
            ];
        });

        $visitorRows = TrafficVisitor::query()
            ->when($period['start'], fn ($q) => $q->whereBetween('visited_at', [$period['start'], $period['end']]))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('page_url', 'like', "%{$search}%")
                        ->orWhere('referrer', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('visited_at')
            ->limit(10000)
            ->get()
            ->map(function ($v) {
                return [
                    'Waktu Akses' => Carbon::parse($v->visited_at)->format('Y-m-d H:i:s'),
                    'Halaman'     => $v->page_url,
                    'Referrer'    => $v->referrer,
                    'Browser'     => $v->browser,
                    'Device'      => $v->device,
                    'OS'          => $v->os,
                    'Negara'      => $v->country,
                    'IP Address'  => $v->ip_address,
                ];
            });

        return [
            'links'   => $linkRows,
            'buttons' => $buttonRows,
            'visitors' => $visitorRows,
        ];
    }

    /**
     * Grafik publik: kunjungan pengunjung per hari untuk 1 bulan terakhir.
     * Hanya mengembalikan data agregat (jumlah), tanpa IP/referrer/UA.
     */
    public function publicChart(): array
    {
        $start = Carbon::today()->subDays(29)->startOfDay();
        $end = Carbon::tomorrow()->subSecond();

        $rows = TrafficVisitor::query()
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(DISTINCT visitor_token) as visitors'))
            ->whereBetween('visited_at', [$start, $end])
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->pluck('visitors', 'date');

        $days = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $days[] = [
                'date'      => $key,
                'label'     => $date->format('d M'),
                'visitors'  => (int) ($rows[$key] ?? 0),
            ];
        }

        return [
            'days'        => $days,
            'max_visitors' => max(1, (int) max(array_column($days, 'visitors'))),
            'total'       => (int) array_sum(array_column($days, 'visitors')),
        ];
    }

    /**
     * Statistik untuk satu hari (hari ini): pengunjung unik & jumlah akses halaman.
     * Hanya data agregat, tanpa IP/referrer/UA.
     */
    public function publicTodayStats(): array
    {
        $start = Carbon::today()->startOfDay();
        $end = Carbon::today()->endOfDay();

        return [
            'date'          => Carbon::today()->translatedFormat('l, d F Y'),
            'visitors'      => (int) TrafficVisitor::query()
                ->whereBetween('visited_at', [$start, $end])
                ->distinct('visitor_token')
                ->count('visitor_token'),
            'views'         => (int) TrafficVisitor::query()
                ->whereBetween('visited_at', [$start, $end])
                ->count(),
            'clicks'        => (int) TrafficClick::query()
                ->whereBetween('clicked_at', [$start, $end])
                ->count(),
            'link_clicks'   => (int) TrafficClick::query()
                ->whereBetween('clicked_at', [$start, $end])
                ->where('click_type', 'link')
                ->count(),
            'button_clicks' => (int) TrafficClick::query()
                ->whereBetween('clicked_at', [$start, $end])
                ->where('click_type', 'button')
                ->count(),
        ];
    }

    private function countUniqueVisitorsBetween(string $startDate, string $endDate): int
    {
        return TrafficVisitor::query()
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->distinct('visitor_token')
            ->count('visitor_token');
    }

    private function dayRange(array $period): array
    {
        if ($period['period'] === 'all') {
            return [];
        }

        $start = ($period['start'] ?? Carbon::today()->subDays(29))->copy();
        $end = ($period['end'] ?? Carbon::today())->copy();

        if ($end->diffInDays($start) > 180) {
            $start = $end->copy()->subDays(180);
        }

        $days = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $days[] = $date->copy();
        }

        return $days;
    }

    private function validDate(?string $date): bool
    {
        if (empty($date)) {
            return false;
        }

        try {
            $parsed = Carbon::parse($date);

            return $parsed->format('Y-m-d') === $date;
        } catch (\Throwable $e) {
            return false;
        }
    }
}