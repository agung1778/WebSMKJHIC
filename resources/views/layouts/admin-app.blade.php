<!DOCTYPE html>
<html lang="id" @if(Cache::get('smk-admin-theme-default') === 'dark') class="dark" @endif>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin Dashboard') — SMK Amaliah 1 & 2</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'Poppins', 'sans-serif'] } } }
        };
    </script>
    <link rel="stylesheet" href="{{ asset('admin/admin.css') }}" />
    @stack('styles')
    <script src="{{ asset('admin/admin.js') }}" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>

<body>
    @php
        // -----------------------------------------------------------------
        // Struktur menu admin (data-driven — mudah ditambah menu baru)
        // routes : pola route yang membuat item aktif (request()->routeIs)
        // url    : nama route untuk tautan
        // -----------------------------------------------------------------
        $menu = [
            [
                'label' => 'Umum',
                'items' => [
                    ['title' => 'Dashboard', 'icon' => 'fa-solid fa-gauge-high', 'routes' => ['admin.dashboard'], 'name' => 'admin.dashboard'],
                ],
            ],
            [
                'label' => 'Sekolah',
                'items' => [
                    ['title' => 'Profil & Tulisan', 'icon' => 'fa-solid fa-book-open', 'routes' => ['admin.writings.index', 'admin.writings.create', 'admin.writings.edit', 'admin.writings.show'], 'name' => 'admin.writings.index'],
                    ['title' => 'Jurusan / Kompetensi', 'icon' => 'fa-solid fa-layer-group', 'routes' => ['admin.majors.*'], 'name' => 'admin.majors.index'],
                    ['title' => 'Program Pendidikan', 'icon' => 'fa-solid fa-graduation-cap', 'routes' => ['admin.programs.*'], 'name' => 'admin.programs.index'],
                    ['title' => 'Info SPMB', 'icon' => 'fa-solid fa-file-circle-check', 'routes' => ['admin.spmb_settings.*'], 'name' => 'admin.spmb_settings.edit'],
                    ['title' => 'Jumlah Siswa', 'icon' => 'fa-solid fa-user-graduate', 'routes' => ['admin.school_settings.*'], 'name' => 'admin.school_settings.edit'],
                    ['title' => 'Menu Navigasi', 'icon' => 'fa-solid fa-bars-staggered', 'routes' => ['admin.navigations.*'], 'name' => 'admin.navigations.index'],
                    ['title' => 'Galeri P5/PKK', 'icon' => 'fa-solid fa-lightbulb', 'routes' => ['admin.pkk.*'], 'name' => 'admin.pkk.index'],
                ],
            ],
            [
                'label' => 'Media & Berita',
                'items' => [
                    ['title' => 'Berita', 'icon' => 'fa-solid fa-newspaper', 'routes' => ['admin.news.*'], 'name' => 'admin.news.index'],
                    ['title' => 'Hero Images', 'icon' => 'fa-solid fa-image', 'routes' => ['admin.image.*'], 'name' => 'admin.image.index'],
                    ['title' => 'Galeri Instagram', 'icon' => 'fa-brands fa-instagram', 'routes' => ['admin.insta-posts.*'], 'name' => 'admin.insta-posts.index'],
                ],
            ],
            [
                'label' => 'Akademik & Kegiatan',
                'items' => [
                    ['title' => 'Guru & Staf', 'icon' => 'fa-solid fa-user-tie', 'routes' => ['admin.teachers.*'], 'name' => 'admin.teachers.index'],
                    ['title' => 'Prestasi', 'icon' => 'fa-solid fa-trophy', 'routes' => ['admin.achievements.*'], 'name' => 'admin.achievements.index'],
                    ['title' => 'Ekstrakurikuler', 'icon' => 'fa-solid fa-futbol', 'routes' => ['admin.extracurriculars.*'], 'name' => 'admin.extracurriculars.index'],
                ],
            ],
            [
                'label' => 'Aset & Relasi',
                'items' => [
                    ['title' => 'Fasilitas', 'icon' => 'fa-solid fa-building', 'routes' => ['admin.facilities.*'], 'name' => 'admin.facilities.index'],
                    ['title' => 'Testimoni', 'icon' => 'fa-solid fa-quote-right', 'routes' => ['admin.testimonials.*'], 'name' => 'admin.testimonials.index'],
                    ['title' => 'Mitra Industri', 'icon' => 'fa-solid fa-handshake', 'routes' => ['admin.partners.*'], 'name' => 'admin.partners.index'],
                ],
            ],
            [
                'label' => 'Monitoring',
                'items' => array_merge(
                    [
                        ['title' => 'Traffic Website', 'icon' => 'fa-solid fa-chart-line', 'routes' => ['admin.traffic.*'], 'name' => 'admin.traffic.index'],
                        ['title' => 'Feeds CuratorIO', 'icon' => 'fa-solid fa-link', 'routes' => ['admin.curator'], 'name' => 'admin.curator'],
                    ],
                    auth()->user()->role === 'superadmin' ? [
                        ['title' => 'Manajemen Admin', 'icon' => 'fa-solid fa-users', 'routes' => ['admin.users', 'admin.users.updateRole', 'admin.users.edit', 'admin.users.update'], 'name' => 'admin.users'],
                    ] : []
                ),
            ],
        ];

        // Status item menu yang sedang aktif (untuk breadcrumb)
        $pageActive = 'Dashboard';
        $pageParent = null;
        foreach ($menu as $sec) {
            foreach ($sec['items'] as $item) {
                $match = array_filter($item['routes'], fn($r) => request()->routeIs($r));
                if ($match) {
                    $pageActive = $item['title'];
                    $pageParent = $sec['label'];
                    break 2;
                }
                if (isset($item['children'])) {
                    foreach ($item['children'] as $c) {
                        if (array_filter($c['routes'] ?? [], fn($r) => request()->routeIs($r))) {
                            $pageActive = $c['title'];
                            $pageParent = $sec['label'];
                            break 3;
                        }
                    }
                }
            }
        }

        $userName = auth()->user()->name;
        $initials = collect(explode(' ', trim($userName)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode('');
        $userRole = auth()->user()->role;

        $quickActions = [
            ['title' => 'Tambah Berita', 'icon' => 'fa-solid fa-newspaper', 'url' => route('admin.news.create')],
            ['title' => 'Tambah Program', 'icon' => 'fa-solid fa-graduation-cap', 'url' => route('admin.programs.create')],
            ['title' => 'Tambah Guru', 'icon' => 'fa-solid fa-user-tie', 'url' => route('admin.teachers.create')],
            ['title' => 'Tambah Prestasi', 'icon' => 'fa-solid fa-trophy', 'url' => route('admin.achievements.create')],
            ['title' => 'Tambah Fasilitas', 'icon' => 'fa-solid fa-building', 'url' => route('admin.facilities.create')],
        ];

        $paletteGroups = [];
        foreach ($menu as $sec) {
            $items = [];
            foreach ($sec['items'] as $item) {
                $url = isset($item['name']) && Route::has($item['name']) ? route($item['name']) : '#';
                $items[] = ['title' => $item['title'], 'icon' => $item['icon'], 'url' => $url, 'kw' => $item['title'] . ' ' . $sec['label']];
            }
            $paletteGroups[] = ['label' => $sec['label'], 'items' => $items];
        }
        $paletteGroups[] = ['label' => 'Aksi Cepat', 'items' => array_map(fn($a) => ['title' => $a['title'], 'icon' => $a['icon'], 'url' => $a['url']], $quickActions)];
    @endphp

    <div class="app-shell">

        {{-- ============ SIDEBAR ============ --}}
        <aside class="sidebar" aria-label="Menu admin">
            <div class="sidebar-brand">
                <img class="logo" src="{{ asset('assets/logo/amaliah_white.png') }}" alt="Logo SMK Amaliah" />
                <div class="brand-text">
                    <div class="brand-name"><span>SMK</span> Amaliah 1 &amp; 2 CIAWI</div>
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
                                $itemUrl = isset($item['name']) && Route::has($item['name']) ? route($item['name']) : '#';
                            @endphp
                            @if (isset($item['children']))
                                <div class="nav-group">
                                    <button type="button" class="nav-group-toggle" @if($itemActive) aria-expanded="true" @endif>
                                        <i class="nav-icon {{ $item['icon'] }}"></i>
                                        <span class="nav-text">{{ $item['title'] }}</span>
                                        <i class="chevron fa-solid fa-chevron-right"></i>
                                    </button>
                                    <div class="nav-submenu">
                                        @foreach ($item['children'] as $child)
                                            @php
                                                $childActive = count(array_filter($child['routes'] ?? [], fn($r) => request()->routeIs($r))) > 0;
                                                $childUrl = isset($child['name']) && Route::has($child['name']) ? route($child['name']) : '#';
                                            @endphp
                                            <a class="nav-link @if($childActive) active @endif" href="{{ $childUrl }}" title="{{ $child['title'] }}">
                                                <i class="nav-icon {{ $child['icon'] ?? 'fa-solid fa-circle' }}" style="font-size:8px"></i>
                                                <span class="nav-text">{{ $child['title'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a class="nav-link @if($itemActive) active @endif" href="{{ $itemUrl }}" title="{{ $item['title'] }}">
                                    <i class="nav-icon {{ $item['icon'] }}"></i>
                                    <span class="nav-text">{{ $item['title'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </nav>

            <div class="sidebar-foot">
                <div class="profile-chip" title="{{ $userName }}" @if(!auth()->user()->is(auth()->user()) == false) @endif>
                    <div class="avatar">{{ strtoupper($initials) }}</div>
                    <div class="foot-text" style="min-width:0">
                        <div class="p-name">{{ $userName }}</div>
                        <div class="p-role">
                            <span class="badge @if($userRole === 'superadmin') badge-violet @else badge-teal @endif" style="padding:2px 8px">{{ $userRole }}</span>
                        </div>
                    </div>
                </div>
                <a class="app-btn app-btn-primary btn-block" href="{{ url('/') }}" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span class="foot-text">Lihat Website</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="app-btn btn-block" style="width:100%" type="submit">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="foot-text">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ MAIN ============ --}}
        <div class="app-main">
            <header class="topbar">
                <button type="button" class="icon-btn md:hidden" data-sidebar-mobile-toggle aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button type="button" class="icon-btn hide-mob" data-sidebar-toggle aria-label="Ciutkan sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>

                <div class="topbar-title">
                    <nav class="topbar-crumb" aria-label="Breadcrumb">
                        <span><a href="{{ route('admin.dashboard') }}">Beranda</a></span>
                        @if ($pageParent && $pageParent !== 'Umum')
                            <i class="fa-solid fa-angle-right sep" style="font-size:9px"></i>
                            <span>{{ $pageParent }}</span>
                        @endif
                        <i class="fa-solid fa-angle-right sep" style="font-size:9px"></i>
                        <span style="color:var(--text-2);font-weight:600">{{ $pageActive }}</span>
                    </nav>
                    <h1 class="topbar-h1">@yield('title', $pageActive)</h1>
                </div>

                <div class="topbar-actions">
                    <button type="button" class="icon-btn search-field" data-open-palette aria-label="Pencarian global (Ctrl+K)"
                        style="width:auto;gap:8px;padding:0 12px">
                        <i class="fa-solid fa-magnifying-glass" style="color:var(--text-3)"></i>
                        <span class="hide-mob" style="font-size:12.5px;color:var(--text-3)">Cari…</span>
                        <kbd style="font-size:10.5px;color:var(--text-3);border:1px solid var(--border);border-radius:6px;padding:1px 6px;font-family:inherit">Ctrl K</kbd>
                    </button>

                    {{-- Notification center --}}
                    <div class="dropdown">
                        <button type="button" class="icon-btn" data-dropdown aria-label="Notifikasi">
                            <i class="fa-regular fa-bell"></i>
                            <span class="tw-pulse" style="position:absolute;top:8px;right:8px;width:7px;height:7px;border-radius:50%;background:var(--brand)"></span>
                        </button>
                        <div class="dropdown-menu" style="display:none;width:300px;max-width:calc(100vw - 32px)">
                            <div style="padding:12px 14px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:700;font-size:13px;color:var(--text)">Notifikasi</div>
                                <div style="font-size:11.5px;color:var(--text-3)">Ringkasan sistem</div>
                            </div>
                            <div class="dropdown-item" style="cursor:default">
                                <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
                                <span>Semua sistem berjalan normal.</span>
                            </div>
                            @if (session('success'))
                                <div class="dropdown-item" style="cursor:default">
                                    <i class="fa-solid fa-circle-info" style="color:var(--blue)"></i>
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
                        <button type="button" class="icon-btn" data-dropdown aria-label="Menu profil" style="width:auto;padding:2px 6px 2px 3px;gap:6px">
                            <span style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700">{{ strtoupper($initials) }}</span>
                            <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--text-3)"></i>
                        </button>
                        <div class="dropdown-menu" style="display:none">
                            <div style="padding:10px 12px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:700;font-size:13px;color:var(--text)">{{ $userName }}</div>
                                <div style="font-size:11.5px;color:var(--text-3);text-transform:capitalize">{{ $userRole }}</div>
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