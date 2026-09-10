<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\TrafficVisitor;
use App\Models\TrafficClick;
use App\Services\TrafficService;

class TrafficController extends Controller
{
    public function __construct(
        protected TrafficService $traffic
    ) {
        $this->middleware('throttle:60,1')->only(['trackPage', 'trackClick']);
    }

    /**
     * Simpan kunjungan halaman (dipanggil otomatis dari tracking JS).
     */
    public function trackPage(Request $request)
    {
        $visitorToken = $request->input('visitor_token');
        $pageUrl = $request->input('page_url');

        if (! is_string($visitorToken) || ! is_string($pageUrl) || $visitorToken === '') {
            return response()->json(['error' => 'invalid payload'], 422);
        }

        $ua = $this->parseUserAgent($request);

        try {
            TrafficVisitor::create([
                'visitor_token' => substr($visitorToken, 0, 191),
                'ip_address'    => $this->sanitizeIp($request->ip()),
                'user_agent'    => mb_substr($request->userAgent() ?? '', 0, 1000),
                'browser'       => $ua['browser'],
                'device'        => $ua['device'],
                'os'            => $ua['os'],
                'country'       => $this->traffic->countryFromRequest($request),
                'referrer'      => $this->truncate($request->input('referrer'), 500),
                'page_url'      => $this->truncate($pageUrl, 500),
                'visited_at'    => Carbon::now(),
            ]);

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'failed'], 500);
        }
    }

    /**
     * Simpan event klik (link / button), dipanggil otomatis dari tracking JS.
     */
    public function trackClick(Request $request)
    {
        $clickType = $request->input('click_type');
        $elementName = $request->input('element_name');

        if (! in_array($clickType, ['link', 'button'], true)
            || ! is_string($elementName)
            || trim($elementName) === ''
        ) {
            return response()->json(['error' => 'invalid payload'], 422);
        }

        $ua = $this->parseUserAgent($request);

        try {
            TrafficClick::create([
                'visitor_token' => $this->truncate($request->input('visitor_token'), 191),
                'click_type'    => $clickType,
                'element_name'  => $this->truncate(trim($elementName), 500),
                'element_url'   => $this->truncate($request->input('element_url'), 1000),
                'page_url'      => $this->truncate($request->input('page_url'), 500),
                'ip_address'    => $this->sanitizeIp($request->ip()),
                'user_agent'    => mb_substr($request->userAgent() ?? '', 0, 1000),
                'browser'       => $ua['browser'],
                'device'        => $ua['device'],
                'os'            => $ua['os'],
                'country'       => $this->traffic->countryFromRequest($request),
                'referrer'      => $this->truncate($request->input('referrer'), 500),
                'clicked_at'    => Carbon::now(),
            ]);

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'failed'], 500);
        }
    }

    /**
     * Halaman dashboard Traffic Website.
     */
    public function index(Request $request)
    {
        $period = $this->traffic->resolvePeriod($request);
        $search = $this->normalizeSearch($request->input('search'));

        $cards = $this->traffic->summaryCards();
        $daily = $this->traffic->dailyStats($period);
        $weekly = $this->traffic->weeklyStats();
        $monthly = $this->traffic->monthlyStats();
        $links = $this->traffic->linkTraffic($period, $search);
        $buttons = $this->traffic->buttonTraffic($period, $search);
        $visitors = $this->traffic->visitorDetails($period, $search);

        $totalLinks = $links['total'];
        $totalButtons = $buttons['total'];

        return view('admin.traffic.index', compact(
            'period',
            'search',
            'cards',
            'daily',
            'weekly',
            'monthly',
            'links',
            'buttons',
            'visitors',
            'totalLinks',
            'totalButtons'
        ));
    }

    /**
     * Export data traffic ke CSV (tanpa package tambahan).
     */
    public function export(Request $request)
    {
        $period = $this->traffic->resolvePeriod($request);
        $search = $this->normalizeSearch($request->input('search'));
        $data = $this->traffic->exportData($period, $search);

        $filename = 'traffic-website-' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $callback = function () use ($data, $period) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Laporan Traffic Website - ' . $period['label']]);
            fputcsv($file, []);

            // Section: Link
            fputcsv($file, ['TRAFIK LINK']);
            fputcsv($file, ['Nama Link', 'URL', 'Jumlah Klik', 'Unique Visitor', 'Klik Terakhir']);
            foreach ($data['links'] as $row) {
                fputcsv($file, $row);
            }
            fputcsv($file, []);

            // Section: Button
            fputcsv($file, ['TRAFIK BUTTON']);
            fputcsv($file, ['Nama Button', 'Lokasi Halaman', 'Jumlah Klik', 'Unique Visitor', 'Klik Terakhir']);
            foreach ($data['buttons'] as $row) {
                fputcsv($file, $row);
            }
            fputcsv($file, []);

            // Section: Visitor Detail
            fputcsv($file, ['DETAIL PENGUNJUNG']);
            fputcsv($file, ['Waktu Akses', 'Halaman', 'Referrer', 'Browser', 'Device', 'OS', 'Negara', 'IP Address']);
            foreach ($data['visitors'] as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type'              => 'text/csv; charset=UTF-8',
            'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
            'X-Content-Type-Options'    => 'nosniff',
        ]);
    }

    private function parseUserAgent(Request $request): array
    {
        return $this->traffic->parseUserAgent($request->userAgent());
    }

    private function sanitizeIp(?string $ip): ?string
    {
        return $ip ? substr($ip, 0, 64) : null;
    }

    private function truncate(mixed $value, int $length): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        return mb_substr($value, 0, $length);
    }

    private function normalizeSearch(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}