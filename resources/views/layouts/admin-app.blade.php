<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Times+New+Roman&display=swap"
        rel="stylesheet" />
    {{-- Bootstrap CSS & JS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        .times {
            font-family: 'Times New Roman', serif;
        }

        .sidebar-link-active {
            color: #6CF600 !important;
            font-weight: 600;
            background-color: #333;
            position: relative;
        }

        .sidebar-link-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 4px;
            border-radius: 0 3px 3px 0;
            background-color: #6CF600;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .collapsible-content.expanded {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }

        /* Sembunyikan scrollbar untuk WebKit (Chrome, Safari) */
        .sidebar::-webkit-scrollbar {
            display: none;
        }

        /* Sembunyikan scrollbar untuk Firefox */
        .sidebar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Sembunyikan scrollbar untuk WebKit (Chrome, Safari) */
        ::-webkit-scrollbar {
            width: 0.5em;
            /* Atau atur lebar menjadi 0 jika Anda ingin menyembunyikannya sepenuhnya */
            display: none;
            /* Opsional: untuk memastikan scrollbar tidak muncul sama sekali */
        }
    </style>
</head>

<body class="text-black flex">

    <aside id="sidebar"
        class="sidebar bg-[#292929] md:w-64 fixed top-0 left-0 bottom-0 z-50 flex flex-col justify-between p-6 transition-transform duration-300 md:translate-x-0 -translate-x-full">
        <div class="flex flex-col h-full">
            <div class="mb-6 flex items-center space-x-3">
                <img alt="Logo SMK Amaliah" class="w-11 h-11" src="{{ asset('assets/logo/amaliah_white.png') }}" />
                <div class="text-white text-xs leading-tight times">
                    <div>
                        <span class="font-bold">SMK</span> Amaliah 1 &amp; 2 CIAWI
                    </div>
                    <div class="italic font-light text-[10px]">Tauhid Is Our Fundament</div>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-6 relative">
                <input id="sidebar-search"
                    class="w-full rounded-full bg-[#D9D9D9] text-xs text-black placeholder-gray-600 px-4 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#6CF600] pl-10"
                    placeholder="Search menu..." type="search" />
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <nav class="text-white text-sm space-y-1 flex-1 overflow-y-auto pr-2">
                {{-- Home --}}
                <a class="relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700
                    @if(request()->routeIs('admin.dashboard')) sidebar-link-active @endif"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Home</span>
                </a>

                {{-- Editor --}}
                <div class="relative">
                    <button id="editor-toggle"
                        class="w-full text-left relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700 focus:outline-none
                            @if(request()->routeIs(['admin.majors.*', 'admin.news.*', 'admin.testimonials.*', 'admin.partners.*', 'admin.facilities.*', 'admin.programs.*', 'admin.teachers.*', 'admin.achievements.*', 'admin.image.*', 'admin.extracurriculars.*', 'admin.writings.*'])) sidebar-link-active @endif">
                        <i class="fas fa-pen-alt w-5 text-center"></i>
                        <span class="flex-1">Editor</span>
                        <i id="editor-arrow"
                            class="fas fa-chevron-right text-xs transition-transform duration-300
                                @if(request()->routeIs(['admin.majors.*', 'admin.news.*', 'admin.testimonials.*', 'admin.partners.*', 'admin.facilities.*', 'admin.programs.*', 'admin.teachers.*', 'admin.achievements.*', 'admin.image.*', 'admin.extracurriculars.*', 'admin.writings.*'])) rotate-90 @endif">
                        </i>
                    </button>
                    <div id="editor-submenu"
                        class="pl-10 space-y-1 collapsible-content
                            @if(request()->routeIs(['admin.majors.*', 'admin.news.*', 'admin.testimonials.*', 'admin.partners.*', 'admin.facilities.*', 'admin.programs.*', 'admin.teachers.*', 'admin.achievements.*', 'admin.image.*', 'admin.extracurriculars.*', 'admin.writings.*'])) expanded @endif">
                        {{-- Sub-menu items --}}
                        <a id="submenu-mainimage"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.image.*')) sidebar-link-active @endif"
                            href="{{ route('admin.image.index') }}">
                            Image
                        </a>
                        <a id="submenu-history"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700  @if(request()->routeIs('admin.writings.*')) sidebar-link-active @endif"
                            href="{{ route('admin.writings.index') }}">Writing</a>
                        <a id="submenu-facility"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.facilities.*')) sidebar-link-active @endif"
                            href="{{ route('admin.facilities.index') }}">Facility</a>
                        <a id="submenu-programs"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.programs.*')) sidebar-link-active @endif"
                            href="{{ route('admin.programs.index') }}">Programs</a>
                        <a id="submenu-major"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.majors.*')) sidebar-link-active @endif"
                            href="{{ route('admin.majors.index') }}">
                            Major
                        </a>
                        <a id="submenu-news"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.news.*')) sidebar-link-active @endif"
                            href="{{ route('admin.news.index') }}">News</a>
                        <a id="submenu-testimonials"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.testimonials.*')) sidebar-link-active @endif"
                            href="{{ route('admin.testimonials.index') }}">
                            Testimonials
                        </a>
                        <a id="submenu-partners"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.partners.*')) sidebar-link-active @endif"
                            href="{{ route('admin.partners.index') }}">
                            Partners
                        </a>
                        <a id="submenu-teachers"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.teachers.*')) sidebar-link-active @endif"
                            href="{{ route('admin.teachers.index') }}">
                            Teachers
                        </a>
                        <a id="submenu-achievement"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.achievements.*')) sidebar-link-active @endif"
                            href="{{ route('admin.achievements.index') }}">
                            Achievement
                        </a>
                        <a id="submenu-extracurricular"
                            class="block p-2 text-xs rounded-md transition hover:bg-gray-700 @if(request()->routeIs('admin.extracurriculars.*')) sidebar-link-active @endif"
                            href="{{ route('admin.extracurriculars.index') }}">
                            Extracurricular
                        </a>

                    </div>
                </div>
                <a class="relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700 @if(request()->routeIs(['admin.users'])) sidebar-link-active
                @endif" href="{{ route('admin.users') }}">
                    <i class="fa-solid fa-circle-user w-5 text-center"></i>
                    <span>Users</span>
                </a>
                <a class="relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700 @if(request()->routeIs(['admin.curator'])) sidebar-link-active
                @endif" href="{{ route('admin.curator') }}">
                    <i class="fa-solid fa-link w-5 text-center"></i>
                    <span>CuratorIO</span>
                </a>
                <a class="relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700" href="https://forms.gle/sveGZa9nd9uX62YE9">
                    <i class="fas fa-comments w-5 text-center"></i>
                    <span>Feedback</span>
                </a>
                <a class="relative flex items-center space-x-3 p-2 rounded-md transition hover:bg-gray-700" href="https://app.jagoan.cloud/">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer space-y-3 pt-6">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full bg-[#6CF600] text-white text-sm font-semibold rounded-lg px-4 py-2.5 flex items-center justify-center space-x-2 hover:bg-[#5bd300] transition">
                        <span>Log Out</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
                <a href="{{ url('/') }}"
                    class="w-full bg-[#6CF600] text-white text-sm font-semibold rounded-lg px-4 py-2.5 flex items-center justify-center space-x-2 hover:bg-[#5bd300] transition">
                    <span>Go to Web</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    </aside>

    <button id="toggleSidebar" class="md:hidden fixed top-4 left-4 z-50 bg-[#6CF600] text-white p-2 rounded-lg shadow">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content flex-1 md:ml-64 p-6 w-full">
        @yield('content')
    </main>


    <!-- Script -->

    <script>
        // Toggle Sidebar
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggleSidebar");
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });

        // Editor Submenu
        const editorToggle = document.getElementById("editor-toggle");
        const editorSubmenu = document.getElementById("editor-submenu");
        const editorArrow = document.getElementById("editor-arrow");
        editorToggle.addEventListener("click", () => {
            editorSubmenu.classList.toggle("expanded");
            editorArrow.classList.toggle("rotate-90");
        });

        // Search functionality
        const searchInput = document.getElementById("sidebar-search");
        const sidebarLinks = document.querySelectorAll("#sidebar-nav .sidebar-link");

        searchInput.addEventListener("keyup", () => {
            let filter = searchInput.value.toLowerCase();
            sidebarLinks.forEach(link => {
                let text = link.textContent.toLowerCase();
                let id = link.id.toLowerCase();
                if (text.includes(filter) || id.includes(filter)) {
                    link.style.display = "";
                } else {
                    link.style.display = "none";
                }
            });
        });
    </script>

</body>

</html>