<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Services\TrafficService;

class PublicTrafficController extends Controller
{
    protected TrafficService $traffic;

    public function __construct(TrafficService $traffic)
    {
        $this->traffic = $traffic;
    }

    /**
     * Halaman traffic publik: menampilkan rekapan bulanan.
     * Hanya data agregat (jumlah), tanpa IP/referrer/UA.
     */
    public function index()
    {
        $monthly = $this->traffic->monthlyStats();
        $chart   = $this->traffic->publicChart(); // 30 hari terakhir
        $today   = $this->traffic->publicTodayStats(); // statistik hari ini

        return view('PublicSide.traffic.index', compact('monthly', 'chart', 'today'));
    }
}
