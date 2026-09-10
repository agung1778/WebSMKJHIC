<!DOCTYPE html>
<html lang="id" @if(Cache::get('smk-admin-theme-default') === 'dark') class="dark" @endif>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') · SMK Amaliah 1 &amp; 2</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: '#63CD00',
                        'brand-strong': '#4FB800',
                        'brand-deep': '#3E9B00',
                        'brand-soft': '#EFF9E3',
                        dark: '#282829',
                        ink: '#1C1C1D',
                        'app-bg': '#F6F8FB'
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css" />
    <link rel="stylesheet" href="{{ asset('admin/admin.css') }}?v={{ @filemtime(public_path('admin/admin.css')) }}" />
    <script defer src="{{ asset('admin/admin.js') }}?v={{ @filemtime(public_path('admin/admin.js')) }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @stack('styles')
</head>

<body class="font-sans">
    @php
        // -----------------------------------------------------------------
        // Struktur menu admin (data-driven — mudah ditambah menu baru)
        // routes : pola route yang membuat item aktif (request()->routeIs)
        // url    : nama route untuk tautan (nama route, bukan URL)
        // icon   : kelas Font Awesome
        // -----------------------------------------------------------------
        $menu = [
            [
                'label' => 'Dashboard',
                'items' => [
                    ['title' => 'Dashboard', 'icon' => 'fa-solid fa-gauge-high', 'routes' => ['admin.dashboard'], 'url' => 'admin.dashboard'],
                ],
            ],
            [
                'label' => 'Website',
                'items' => [
                    ['title' => 'Home & Tulisan', 'icon' => 'fa-solid fa-house', 'routes' => ['admin.writings.*'], 'url' => 'admin.writings.index'],
                    ['title' => 'Hero & Media', 'icon' => 'fa-solid fa-images', 'routes' => ['admin.image.*'], 'url' => 'admin.image.index'],
                    ['title' => 'Menu Navigasi', 'icon' => 'fa-solid fa-bars-staggered', 'routes' => ['admin.navigations.*'], 'url' => 'admin.navigations.index'],
                    ['title' => 'Feed Instagram', 'icon' => 'fa-brands fa-instagram', 'routes' => ['admin.insta-posts.*'], 'url' => 'admin.insta-posts.index'],
                    ['title' => 'Jumlah Peserta Didik', 'icon' => 'fa-solid fa-users', 'routes' => ['admin.school_settings.*'], 'url' => 'admin.school_settings.edit'],
                ],
            ],
            [
                'label' => 'Konten',
                'items' => [
                    ['title' => 'Berita', 'icon' => 'fa-solid fa-newspaper', 'routes' => ['admin.news.*'], 'url' => 'admin.news.index'],
                    ['title' => 'Jurusan', 'icon' => 'fa-solid fa-layer-group', 'routes' => ['admin.majors.*'], 'url' => 'admin.majors.index'],
                    ['title' => 'Program', 'icon' => 'fa-solid fa-graduation-cap', 'routes' => ['admin.programs.*'], 'url' => 'admin.programs.index'],
                    ['title' => 'Fasilitas', 'icon' => 'fa-solid fa-building', 'routes' => ['admin.facilities.*'], 'url' => 'admin.facilities.index'],
                    ['title' => 'Mitra Industri', 'icon' => 'fa-solid fa-handshake', 'routes' => ['admin.partners.*'], 'url' => 'admin.partners.index'],
                    ['title' => 'Testimoni', 'icon' => 'fa-solid fa-quote-right', 'routes' => ['admin.testimonials.*'], 'url' => 'admin.testimonials.index'],
                    ['title' => 'Galeri P5/PKK', 'icon' => 'fa-solid fa-lightbulb', 'routes' => ['admin.pkk.*'], 'url' => 'admin.pkk.index'],
                ],
            ],
            [
                'label' => 'Akademik',
                'items' => [
                    ['title' => 'Guru & Staf', 'icon' => 'fa-solid fa-user-tie', 'routes' => ['admin.teachers.*'], 'url' => 'admin.teachers.index'],
                    ['title' => 'Prestasi', 'icon' => 'fa-solid fa-trophy', 'routes' => ['admin.achievements.*'], 'url' => 'admin.achievements.index'],
                    ['title' => 'Ekstrakurikuler', 'icon' => 'fa-solid fa-futbol', 'routes' => ['admin.extracurriculars.*'], 'url' => 'admin.extracurriculars.index'],
                ],
            ],
            [
                'label' => 'Pengaturan',
                'items' => [
                    ['title' => 'Info SPMB', 'icon' => 'fa-solid fa-file-circle-check', 'routes' => ['admin.spmb_settings.*'], 'url' => 'admin.spmb_settings.edit'],
                ],
            ],
            [
                'label' => 'Monitoring',
                'items' => array_merge(
                    [
                        ['title' => 'Traffic Website', 'icon' => 'fa-solid fa-chart-line', 'routes' => ['admin.traffic.*'], 'url' => 'admin.traffic.index'],
                        ['title' => 'Feeds CuratorIO', 'icon' => 'fa-solid fa-rss', 'routes' => ['admin.curator'], 'url' => 'admin.curator'],
                    ],
                    auth()->user()->role === 'superadmin' ? [
                        ['title' => 'Manajemen Admin', 'icon' => 'fa-solid fa-users', 'routes' => ['admin.users', 'admin.users.edit', 'admin.users.update', 'admin.users.updateRole'], 'url' => 'admin.users'],
                    ] : []
                ),
            ],
        ];

        // Status item menu yang aktif (untuk breadcrumb)
        $pageActive = 'Dashboard';
        $pageParent = null;
        foreach ($menu as $sec) {
            foreach ($sec['items'] as $item) {
                if (array_filter($item['routes'], fn($r) => request()->routeIs($r))) {
                    $pageActive = $item['title'];
                    $pageParent = $sec['label'];
                    break 2;
                }
            }
        }

        $user = auth()->user();
        $userName = $user->name;
        $userRole = $user->role;
        $userEmail = $user->email;
        $initials = strtoupper(collect(explode(' ', trim($userName)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode(''));
        $hasAvatar = $user->avatar && \Storage::disk('public')->exists($user->avatar);

        $todayId = now()->locale('id')->translatedFormat('l, d F Y');
        $hour = (int) now()->format('H');
        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 19 ? 'Selamat Sore' : 'Selamat Malam'));

        $quickActions = [
            ['title' => 'Tambah Berita', 'icon' => 'fa-solid fa-newspaper', 'url' => route('admin.news.create')],
            ['title' => 'Tambah Jurusan', 'icon' => 'fa-solid fa-layer-group', 'url' => route('admin.majors.create')],
            ['title' => 'Tambah Program', 'icon' => 'fa-solid fa-graduation-cap', 'url' => route('admin.programs.create')],
            ['title' => 'Tambah Guru', 'icon' => 'fa-solid fa-user-tie', 'url' => route('admin.teachers.create')],
            ['title' => 'Tambah Prestasi', 'icon' => 'fa-solid fa-trophy', 'url' => route('admin.achievements.create')],
            ['title' => 'Tambah Fasilitas', 'icon' => 'fa-solid fa-building', 'url' => route('admin.facilities.create')],
            ['title' => 'Atur Info SPMB', 'icon' => 'fa-solid fa-file-circle-check', 'url' => route('admin.spmb_settings.edit')],
        ];

        $paletteGroups = [];
        foreach ($menu as $sec) {
            $items = [];
            foreach ($sec['items'] as $item) {
                $u = Route::has($item['url']) ? route($item['url']) : '#';
                $items[] = ['title' => $item['title'], 'icon' => $item['icon'], 'url' => $u, 'kw' => $item['title'] . ' ' . $sec['label']];
            }
            $paletteGroups[] = ['label' => $sec['label'], 'items' => $items];
        }
        $paletteGroups[] = ['label' => 'Aksi Cepat', 'items' => array_map(fn($a) => ['title' => $a['title'], 'icon' => $a['icon'], 'url' => $a['url']], $quickActions)];
    @endphp

    <div class="app-shell">

        {{-- ============ SIDEBAR ============ --}}
        <aside class="sidebar" aria-label="Menu admin">
            <div class="sidebar-brand">
                <div class="logo-wrap">
                    <img src="{{ asset('assets/logo/amaliah_white.png') }}" alt="Logo SMK Amaliah" />
                </div>
                <div class="brand-text">
                    <div class="brand-name">SMK Amaliah 1 &amp; 2</div>
                    <div class="brand-tag">Tauhid Is Our Fundament</div>
                </div>
            </div>

            <nav class="sidebar-scroll">
                @foreach ($menu as $section)
                    <div class="menu-section">
                        <div class="menu-label">{{ $section['label'] }}</div>
                        @foreach ($section['items'] as $item)
                            @php
                                $itemActive = count(array_filter($item['routes'], fn($r) => request()->routeIs($r))) > 0;
                                $itemUrl = Route::has($item['url']) ? route($item['url']) : '#';
                            @endphp
                            <a class="nav-link @if($itemActive) active @endif" href="{{ $itemUrl }}" title="{{ $item['title'] }}">
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <span class="nav-text">{{ $item['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </nav>

            <div class="sidebar-foot">
                <div class="profile-chip" title="{{ $userName }}">
                    <div class="avatar">
                        @if($hasAvatar)
                            <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="{{ $userName }}" />
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="foot-text" style="min-width:0">
                        <div class="p-name">{{ $userName }}</div>
                        <div class="p-role">
                            <span class="badge @if($userRole === 'superadmin') badge-violet @else badge-brand @endif" style="padding:2px 8px;text-transform:capitalize">{{ $userRole }}</span>
                        </div>
                    </div>
                </div>
                <a class="app-btn btn-block" style="width:100%" href="{{ url('/') }}" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span class="foot-text">Lihat Website</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="app-btn app-btn-danger btn-block" style="width:100%" type="submit">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="foot-text">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ MAIN ============ --}}
        <div class="app-main">
            <header class="topbar">
                <button type="button" class="icon-btn show-mob" data-sidebar-mobile-toggle aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button type="button" class="icon-btn hide-mob" data-sidebar-toggle aria-label="Ciutkan sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>

                <div class="topbar-title">
                    <nav class="topbar-crumb" aria-label="Breadcrumb">
                        <span><a href="{{ route('admin.dashboard') }}">Beranda</a></span>
                        @if ($pageParent && $pageParent !== 'Dashboard')
                            <i class="fa-solid fa-angle-right sep" style="font-size:9px"></i>
                            <span>{{ $pageParent }}</span>
                        @endif
                        <i class="fa-solid fa-angle-right sep" style="font-size:9px"></i>
                        <span style="color:var(--text-2);font-weight:600">{{ $pageActive }}</span>
                    </nav>
                    <h1 class="topbar-h1">@hasSection('title') @yield('title') @else {{ $pageActive }} @endif</h1>
                </div>

                <div class="topbar-greet">
                    <span class="tg-hi">{{ $greeting }}, {{ $userName }} 👋</span>
                    <span class="tg-date"><i class="fa-regular fa-calendar"></i>{{ $todayId }}</span>
                </div>

                <div class="topbar-actions">
                    {{-- Search / palette --}}
                    <button type="button" class="icon-btn search-field" data-open-palette aria-label="Pencarian global (Ctrl+K)"
                        style="width:auto;gap:8px;padding:0 13px;cursor:text">
                        <i class="fa-solid fa-magnifying-glass" style="color:var(--text-3);font-size:13px"></i>
                        <span class="hide-mob" style="font-size:12.5px;color:var(--text-3)">Cari…</span>
                        <kbd style="font-size:10px;color:var(--text-3);border:1px solid var(--border);border-radius:7px;padding:2px 7px;font-family:inherit">Ctrl K</kbd>
                    </button>

                    {{-- Notification center --}}
                    <div class="dropdown">
                        <button type="button" class="icon-btn" data-dropdown aria-label="Notifikasi">
                            <i class="fa-regular fa-bell"></i>
                            @if(session('success'))
                                <span class="dot-badge"></span>
                            @endif
                        </button>
                        <div class="dropdown-menu" style="display:none;width:320px;max-width:calc(100vw - 32px)">
                            <div style="padding:13px 15px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:700;font-size:13.5px;color:var(--text)">Notifikasi</div>
                                <div style="font-size:11.5px;color:var(--text-3)">Ringkasan sistem</div>
                            </div>
                            <div class="dropdown-item" style="cursor:default">
                                <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
                                <span>Semua sistem berjalan normal.</span>
                            </div>
                            @if (session('success'))
                                <div class="dropdown-item" style="cursor:default">
                                    <i class="fa-solid fa-circle-info" style="color:var(--brand)"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif
                            <div class="dropdown-sep"></div>
                            <a class="dropdown-item" href="{{ route('admin.traffic.index') }}">
                                <i class="fa-solid fa-chart-line"></i><span>Lihat statistik traffic</span>
                            </a>
                            @if ($userRole === 'superadmin')
                                <a class="dropdown-item" href="{{ route('admin.users') }}">
                                    <i class="fa-solid fa-users"></i><span>Kelola admin</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Quick action --}}
                    <div class="dropdown">
                        <button type="button" class="icon-btn" data-dropdown aria-label="Aksi cepat">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <div class="dropdown-menu" style="display:none">
                            @foreach ($quickActions as $qa)
                                <a class="dropdown-item" href="{{ $qa['url'] }}">
                                    <i class="{{ $qa['icon'] }}"></i><span>{{ $qa['title'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Theme toggle --}}
                    <button type="button" class="icon-btn" data-theme-toggle aria-label="Mode terang / gelap">
                        <i class="fa-regular fa-sun" data-theme-icon-sun style="display:none"></i>
                        <i class="fa-regular fa-moon" data-theme-icon-moon></i>
                    </button>

                    {{-- Profile --}}
                    <div class="dropdown">
                        <button type="button" class="icon-btn" data-dropdown aria-label="Menu profil" style="width:auto;padding:3px 8px 3px 4px;gap:8px">
                            <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,#282829,#3E3E40);color:#63CD00;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;overflow:hidden">
                                @if($hasAvatar)
                                    <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover" />
                                @else
                                    {{ $initials }}
                                @endif
                            </span>
                            <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--text-3)"></i>
                        </button>
                        <div class="dropdown-menu" style="display:none">
                            <div style="padding:11px 13px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:700;font-size:13px;color:var(--text)">{{ $userName }}</div>
                                <div style="font-size:11.5px;color:var(--text-3);text-transform:capitalize">{{ $userRole }} · {{ $userEmail }}</div>
                            </div>
                            <a class="dropdown-item" href="{{ url('/') }}" target="_blank"><i class="fa-solid fa-globe"></i><span>Lihat website</span></a>
                            @if ($userRole === 'superadmin')
                                <a class="dropdown-item" href="{{ route('admin.users') }}"><i class="fa-solid fa-user-gear"></i><span>Manajemen admin</span></a>
                            @endif
                            <div class="dropdown-sep"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item danger"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Keluar</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Toast flash (dibaca admin.js) --}}
    @if (session('success'))
        <div style="display:none" data-toast-flash="success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="display:none" data-toast-flash="error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div style="display:none" data-toast-errors>{!! json_encode($errors->all()) !!}</div>
    @endif

    <script>
        window.__palette = @json($paletteGroups);
    </script>

    @stack('scripts')
</body>

</html>