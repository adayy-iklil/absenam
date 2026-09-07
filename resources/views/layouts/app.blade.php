<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIX-PRESENCE - Sistem Absensi Digital SMK Negeri 6 Jakarta')</title>
    
    <!-- Favicon Resmi SMKN 6 Jakarta (Square 1:1 Aspect Ratio - 100% Crisp) -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon-square.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-square.png') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans (Logo & Judul) & Inter (Isi Dashboard, Form, Tabel) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#172554',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet JS Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 15px; /* Base size: 14-16px */
            line-height: 1.5;
        }
        h1, h2, h3, h4, h5, h6, .brand-title, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .clean-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
        }
        .dark .clean-card {
            background-color: #0f172a;
            border: 1px solid #1e293b;
        }

        /* Responsive Mobile Bottom Nav Margin */
        @media (max-width: 767px) {
            main {
                padding-bottom: 6.5rem !important;
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-200">

    <!-- Top Navigation Bar (Desktop & Mobile Header) -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- SMKN 6 Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Negeri 6 Jakarta" class="h-10 sm:h-11 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                        <div class="flex flex-col">
                            <span class="font-heading font-semibold text-lg sm:text-xl tracking-tight text-slate-900 dark:text-white leading-none">
                                SIX-PRESENCE
                            </span>
                            <span class="font-heading font-semibold text-[10px] sm:text-[11px] text-brand-600 dark:text-brand-400 tracking-wide mt-0.5">SMK NEGERI 6 JAKARTA</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-gauge text-brand-600"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.students') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Siswa</a>
                            <a href="{{ route('admin.classes') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Kelas</a>
                            <a href="{{ route('admin.departments') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Jurusan</a>
                            <a href="{{ route('admin.teachers') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Guru</a>
                            <a href="{{ route('admin.reports') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Laporan</a>
                        @elseif(auth()->user()->isTeacher())
                            <a href="{{ route('teacher.dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-gauge text-brand-600"></i> Dashboard
                            </a>
                            <a href="{{ route('teacher.attendance') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Daftar Absensi</a>
                            <a href="{{ route('teacher.prayer') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Absensi Sholat</a>
                        @elseif(auth()->user()->isStudent())
                            <a href="{{ route('student.dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-house text-brand-600"></i> Dashboard
                            </a>
                            <a href="{{ route('student.history') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Riwayat Kehadiran</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Actions: Dark Mode & Profile Dropdown -->
                <div class="flex items-center gap-2.5">
                    
                    <button id="themeToggle" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors focus:outline-none" title="Toggle Dark/Light Mode">
                        <i id="themeToggleIcon" class="fa-solid fa-moon text-sm"></i>
                    </button>

                    @auth
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button type="button" onclick="toggleProfileMenu(event)" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-brand-600 text-white font-heading font-bold flex items-center justify-center text-xs shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 leading-tight">{{ Str::title(mb_strtolower(auth()->user()->name)) }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    @if(auth()->user()->isStudent())
                                        {{ (auth()->user()->student?->gender ?? 'L') == 'P' ? 'Siswi' : 'Siswa' }}
                                    @elseif(auth()->user()->isTeacher())
                                        {{ (auth()->user()->teacher?->gender ?? 'L') == 'P' ? 'Guru (Ibu)' : 'Guru (Bapak)' }}
                                    @else
                                        Admin System
                                    @endif
                                </span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <!-- Dropdown Menu Content -->
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 py-2 hidden transition-all z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Str::title(mb_strtolower(auth()->user()->name)) }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                <i class="fa-solid fa-user-gear text-slate-400 w-4"></i> Pengaturan Profil
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar / Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-sm hover:bg-brand-700 shadow-md shadow-brand-500/20 transition-all">
                        Login
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6 pb-24 md:pb-6">
        @hasSection('header_title')
        <div class="mb-5 sm:mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="font-heading text-xl sm:text-2xl lg:text-3xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    @yield('header_title')
                </h1>
                @hasSection('header_subtitle')
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
                    @yield('header_subtitle')
                </p>
                @endif
            </div>
            @yield('header_action')
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Floating Bar for Mobile Responsive Quick Access (Mobile Only) -->
    @auth
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden px-4 py-2">
        <div class="flex items-center justify-around">
            @if(auth()->user()->isStudent())
                <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-house text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('student.history') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    <span>Riwayat</span>
                </a>
                <a href="{{ route('profile.show') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-user text-lg"></i>
                    <span>Profil</span>
                </a>
            @elseif(auth()->user()->isTeacher())
                <a href="{{ route('teacher.dashboard') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-gauge text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('teacher.attendance') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-list-check text-lg"></i>
                    <span>Absensi</span>
                </a>
                <a href="{{ route('teacher.prayer') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-mosque text-lg"></i>
                    <span>Sholat</span>
                </a>
                <a href="{{ route('profile.show') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-user text-lg"></i>
                    <span>Profil</span>
                </a>
            @elseif(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-gauge text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.students') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-user-graduate text-lg"></i>
                    <span>Siswa</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-file-invoice text-lg"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('profile.show') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span>Profil</span>
                </a>
            @endif
        </div>
    </div>
    @endauth

    <!-- Footer (Hidden on small mobile screens to save space) -->
    <footer class="hidden sm:block bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center justify-center sm:justify-start gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 6" class="h-6 w-auto object-contain">
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                    &copy; {{ date('Y') }} <span class="font-heading font-semibold text-slate-800 dark:text-slate-200">SMK NEGERI 6 JAKARTA</span> — Sistem Absensi Digital Sekolah.
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center justify-center gap-4 text-xs text-slate-400 font-medium">
                <span><i class="fa-solid fa-shield-halved text-brand-600"></i> Terverifikasi Geolocation & Live Selfie</span>
            </div>
        </div>
    </footer>

    <!-- Instant Profile Dropdown & Dark Mode JS -->
    <script>
        function toggleProfileMenu(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            dropdown?.classList.toggle('hidden');
        }

        window.addEventListener('click', (event) => {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });

        const themeToggleBtn = document.getElementById('themeToggle');
        const themeToggleIcon = document.getElementById('themeToggleIcon');
        
        const currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
        }

        themeToggleBtn?.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeToggleIcon.classList.replace('fa-sun', 'fa-moon');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeToggleIcon.classList.replace('fa-moon', 'fa-sun');
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
