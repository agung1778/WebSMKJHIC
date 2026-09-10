@extends('layouts.admin-app')

@section('title', 'Dashboard')

@section('content')
    @php
        $tc = $trafficCards['avg'] ?? [];
        $fmt = fn ($n) => number_format((float) $n, 0, ',', '.');
        $sections = [
            [
                'label' => '🏫 Sekolah',
                'cards' => [
                    ['title' => 'Profil & Tulisan', 'desc' => 'Konten profil sekolah', 'emoji' => '📖', 'count' => $stats['writings'], 'route' => route('admin.writings.index'), 'c' => 'green'],
                    ['title' => 'Jurusan / Kompetensi', 'desc' => 'Bidang keahlian & jurusan', 'emoji' => '🧭', 'count' => $stats['majors'], 'route' => route('admin.majors.index'), 'c' => 'violet'],
                    ['title' => 'Program Pendidikan', 'desc' => 'Program unggulan sekolah', 'emoji' => '🎓', 'count' => $stats['programs'], 'route' => route('admin.programs.index'), 'c' => 'amber'],
                    ['title' => 'Info SPMB', 'desc' => 'Pengaturan SPMB', 'emoji' => '🗒️', 'count' => null, 'route' => route('admin.spmb_settings.edit'), 'c' => 'blue'],
                    ['title' => 'Jumlah Siswa', 'desc' => 'Statistik siswa sekolah', 'emoji' => '🧑‍🎓', 'count' => $stats['students'], 'route' => route('admin.school_settings.edit'), 'c' => 'teal'],
                    ['title' => 'Menu Navigasi', 'desc' => 'Navigasi menu website', 'emoji' => '🗂️', 'count' => $stats['navigations'], 'route' => route('admin.navigations.index'), 'c' => 'orange'],
                    ['title' => 'Galeri P5/PKK', 'desc' => 'Dokumentasi projek', 'emoji' => '💡', 'count' => $stats['pkk'], 'route' => route('admin.pkk.index'), 'c' => 'fuchsia'],
                ],
            ],
            [
                'label' => '📢 Media & Berita',
                'cards' => [
                    ['title' => 'Berita', 'desc' => 'Artikel & informasi terbaru', 'emoji' => '📰', 'count' => $stats['news'], 'route' => route('admin.news.index'), 'c' => 'blue'],
                    ['title' => 'Hero Images', 'desc' => 'Banner gambar utama', 'emoji' => '🖼️', 'count' => $stats['images'], 'route' => route('admin.image.index'), 'c' => 'sky'],
                    ['title' => 'Galeri Instagram', 'desc' => 'Feed media sosial', 'emoji' => '📸', 'count' => $stats['instaPosts'], 'route' => route('admin.insta-posts.index'), 'c' => 'rose'],
                ],
            ],
            [
                'label' => '🎖️ Akademik & Kegiatan',
                'cards' => [
                    ['title' => 'Guru & Staf', 'desc' => 'Tenaga pendidik sekolah', 'emoji' => '👨‍🏫', 'count' => $stats['teachers'], 'route' => route('admin.teachers.index'), 'c' => 'sky'],
                    ['title' => 'Prestasi', 'desc' => 'Pencapaian siswa', 'emoji' => '🏆', 'count' => $stats['achievements'], 'route' => route('admin.achievements.index'), 'c' => 'amber'],
                    ['title' => 'Ekstrakurikuler', 'desc' => 'Kegiatan siswa', 'emoji' => '⚽', 'count' => $stats['extracurriculars'], 'route' => route('admin.extracurriculars.index'), 'c' => 'fuchsia'],
                ],
            ],
            [
                'label' => '🤝 Aset & Relasi',
                'cards' => [
                    ['title' => 'Fasilitas', 'desc' => 'Sarana & prasarana', 'emoji' => '🏫', 'count' => $stats['facilities'], 'route' => route('admin.facilities.index'), 'c' => 'teal'],
                    ['title' => 'Testimoni', 'desc' => 'Kata orang tua / alumni', 'emoji' => '💬', 'count' => $stats['testimonials'], 'route' => route('admin.testimonials.index'), 'c' => 'indigo'],
                    ['title' => 'Mitra Industri', 'desc' => 'Kerja sama industri', 'emoji' => '🤝', 'count' => $stats['partners'], 'route' => route('admin.partners.index'), 'c' => 'violet'],
                ],
            ],
        ];

        $extraCards = [
            ['title' => 'Traffic Website', 'desc' => 'Statistik pengunjung', 'emoji' => '📊', 'count' => $fmt($tc['total_visitors'] ?? 0), 'route' => route('admin.traffic.index'), 'c' => 'green'],
        ];
        if (auth()->user()->role === 'superadmin') {
            $extraCards[] = ['title' => 'Manajemen Admin', 'desc' => 'Kelola akun pengguna', 'emoji' => '👥', 'count' => $stats['users'], 'route' => route('admin.users'), 'c' => 'amber'];
        }
    @endphp

    <div class="animate-fadein space-y-8">
        {{-- ============ BANNER ============ --}}
        <div class="dashboard-banner">
            <div class="banner-grid"></div>
            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6 p-6 lg:p-8">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="banner-logo">
                        <img src="{{ asset('assets/logo/amaliah_white.png') }}" alt="Logo SMK Amaliah" />
                    </div>
                    <div class="min-w-0">
                        <div class="banner-greet">Halo, {{ Auth::user()->name }} 👋</div>
                        <div class="banner-sub">Selamat datang di Panel Admin SMK Amaliah 1 &amp; 2. Kelola seluruh konten website dengan mudah.</div>
                        <div class="flex items-center gap-2 mt-4" style="font-size:20px">
                            <span title="Sekolah">🏫</span>
                            <span title="Belajar">📚</span>
                            <span title="Lulus">🎓</span>
                            <span title="Semangat">⭐</span>
                            <span title="Sukses">🚀</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="banner-emoji">🏫</div>
                    <div class="flex flex-col items-start gap-2">
                        @if ($spmb)
                            <span class="badge @if ($spmb->status === 'Buka') badge-published @else badge-archived @endif" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);color:#fff">
                                <i class="fa-solid fa-door-open"></i>
                                Info SPMB: {{ $spmb->status }}
                            </span>
                        @endif
                        <span class="badge" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);color:#fff">
                            <i class="fa-regular fa-calendar"></i><span id="current-date"></span>
                        </span>
                        <a href="{{ url('/') }}" target="_blank" class="banner-cta">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Lihat Website
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ TRAFFIC ============ --}}
        <section>
            <div class="flex items-center gap-2 mb-4">
                <span class="section-emoji">📈</span>
                <h2 class="dashboard-section-title">Lalu Lintas Website</h2>
                <span class="ml-auto">
                    <a href="{{ route('admin.traffic.index') }}" class="text-[12.5px] font-semibold inline-flex items-center gap-1.5 transition" style="color:var(--brand)">
                        Detail Traffic <i class="fa-solid fa-arrow-right" style="font-size:10px"></i>
                    </a>
                </span>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 dash-traffic">
                @php
                    $traffic = [
                        ['label' => 'Pengunjung Hari Ini', 'value' => $fmt($tc['today_visitors'] ?? 0), 'emoji' => '👀', 'c' => 'green'],
                        ['label' => 'Pengunjung 7 Hari', 'value' => $fmt($tc['week_visitors'] ?? 0), 'emoji' => '📅', 'c' => 'sky'],
                        ['label' => 'Pengunjung 30 Hari', 'value' => $fmt($tc['month_visitors'] ?? 0), 'emoji' => '🗓️', 'c' => 'amber'],
                        ['label' => 'Total Pengunjung', 'value' => $fmt($tc['total_visitors'] ?? 0), 'emoji' => '👥', 'c' => 'violet'],
                        ['label' => 'Klik Link', 'value' => $fmt($tc['total_link_clicks'] ?? 0), 'emoji' => '🔗', 'c' => 'blue'],
                        ['label' => 'Klik Button', 'value' => $fmt($tc['total_button_clicks'] ?? 0), 'emoji' => '🖱️', 'c' => 'teal'],
                    ];
                @endphp
                @foreach ($traffic as $t)
                    <div class="stat-card">
                        <div class="stat-top">
                            <div>
                                <div class="stat-value">{{ $t['value'] }}</div>
                                <div class="stat-label">{{ $t['label'] }}</div>
                            </div>
                            <span style="width:40px;height:40px;border-radius:12px;background:var(--{{ $t['c'] }}-soft);display:flex;align-items:center;justify-content:center;font-size:20px;line-height:1">
                                {{ $t['emoji'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ============ FITUR CRUD ============ --}}
        <section>
            <div class="flex items-center gap-2 mb-4">
                <span class="section-emoji">🧰</span>
                <h2 class="dashboard-section-title">Fitur Konten</h2>
                <span class="ml-auto text-[12px] font-medium" style="color:var(--text-3)">Klik kartu untuk mengelola</span>
            </div>

            <div class="space-y-8">
                @foreach ($sections as $section)
                    <div>
                        <div class="dashboard-subsection">{{ $section['label'] }}</div>
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($section['cards'] as $card)
                                <a href="{{ $card['route'] }}" class="feature-card">
                                    <span class="feature-icon" style="background:var(--{{ $card['c'] }}-soft)">{{ $card['emoji'] }}</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="feature-title">{{ $card['title'] }}</div>
                                        <div class="feature-desc">{{ $card['desc'] }}</div>
                                    </div>
                                    @if ($card['count'] !== null)
                                        <span class="feature-count">{{ $fmt($card['count']) }}</span>
                                    @endif
                                    <i class="fa-solid fa-chevron-right feature-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div>
                    <div class="dashboard-subsection">📋 Monitoring &amp; Lainnya</div>
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($extraCards as $card)
                            <a href="{{ $card['route'] }}" class="feature-card">
                                <span class="feature-icon" style="background:var(--{{ $card['c'] }}-soft)">{{ $card['emoji'] }}</span>
                                <div class="min-w-0 flex-1">
                                    <div class="feature-title">{{ $card['title'] }}</div>
                                    <div class="feature-desc">{{ $card['desc'] }}</div>
                                </div>
                                <span class="feature-count">{{ $fmt($card['count']) }}</span>
                                <i class="fa-solid fa-chevron-right feature-arrow"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
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