<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIX-PRESENCE - SMKN 6 JAKARTA</title>
    
    <!-- Favicon Resmi SMKN 6 Jakarta (Square 1:1 Aspect Ratio - 100% Crisp) -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon-square.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-square.png') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans (Titles & Brand) & Inter (Body & Forms) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
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
    
    <style>
        body { font-family: 'Inter', sans-serif; font-size: 15px; }
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Aesthetic Logo Splash Animation */
        @keyframes logoAestheticPulse {
            0% {
                transform: translateY(10px) scale(0.9);
                filter: drop-shadow(0 0 15px rgba(37, 99, 235, 0.3));
                opacity: 0.3;
            }
            50% {
                transform: translateY(-8px) scale(1.06);
                filter: drop-shadow(0 0 35px rgba(37, 99, 235, 0.8));
                opacity: 1;
            }
            100% {
                transform: translateY(0px) scale(1);
                filter: drop-shadow(0 0 20px rgba(37, 99, 235, 0.5));
                opacity: 1;
            }
        }
        .animate-logo-splash {
            animation: logoAestheticPulse 1.6s cubic-bezier(0.34, 1.56, 0.64, 1) infinite alternate;
        }

        /* Glowing Ring Loader */
        @keyframes ringSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .ring-spinner {
            animation: ringSpin 1.2s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-200">

    <!-- Aesthetic Animated Splash Screen Loader (Only on First Website Visit per Browser Session) -->
    <div id="splashScreen" class="fixed inset-0 z-50 bg-slate-950 flex flex-col items-center justify-center transition-opacity duration-700">
        <div class="relative flex flex-col items-center">
            <!-- Animated Glowing Rings -->
            <div class="absolute -inset-6 rounded-full bg-brand-500/20 blur-2xl animate-pulse"></div>
            
            <!-- SMKN 6 Animated Logo -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 6 Jakarta" class="h-28 w-auto object-contain animate-logo-splash z-10 mb-6">
            
            <!-- Shimmering App Title -->
            <h2 class="font-heading text-2xl font-bold text-white tracking-tight z-10">SIX-PRESENCE</h2>
            <p class="font-heading text-xs text-brand-400 font-semibold uppercase tracking-widest mt-1 z-10">SMK NEGERI 6 JAKARTA</p>

            <!-- Loading Progress Bar -->
            <div class="w-36 h-1 bg-slate-800 rounded-full mt-6 overflow-hidden z-10">
                <div class="h-full bg-brand-500 rounded-full w-0 transition-all duration-1000" id="splashProgressBar"></div>
            </div>
        </div>
    </div>

    <div class="max-w-md w-full my-6">
        
        <!-- Main Login Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-200/80 dark:border-slate-800 backdrop-blur-md">
            
            <!-- School Header with Official SMKN 6 Logo & Plus Jakarta Sans SemiBold Header -->
            <div class="flex flex-col items-center text-center mb-8">
                <div class="mb-4 relative">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Resmi SMK Negeri 6 Jakarta" class="h-24 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300">
                </div>
                
                <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    SIX-PRESENCE
                </h1>
                <p class="font-heading text-xs font-semibold text-brand-600 dark:text-brand-400 mt-1 uppercase tracking-wider">
                    Sistem Absensi Digital SMK NEGERI 6 JAKARTA
                </p>
            </div>

            <!-- Flash Error Message -->
            @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-base flex-shrink-0"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-base flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            <!-- Direct Single Login Form (Inter Font) -->
            <form action="{{ url('/login') }}" method="POST" class="space-y-5 text-sm">
                @csrf
                
                <div>
                    <label for="email" class="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Email / NIS
                    </label>
                    <div class="relative">
                        <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="Masukkan NIS atau Email Anda"
                            class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-normal text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all">
                        <i class="fa-solid fa-user absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            placeholder="Masukkan Password Anda"
                            class="w-full pl-10 pr-10 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-normal text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="passwordToggleIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="font-medium text-slate-600 dark:text-slate-400">Ingat Saya</span>
                    </label>
                </div>

                <!-- Clean Button Text without Icon -->
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-lg shadow-brand-500/25 transition-all text-center">
                    Login
                </button>
            </form>

            <!-- Quick Demo Login Section (Guru & Siswa) -->
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 text-center uppercase tracking-wider mb-3">
                    Akses Cepat Demo Login:
                </p>
                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" onclick="fillDemoLogin('budi.guru@absenam.sch.id', 'Rahasia6#')" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold text-center border border-slate-200 dark:border-slate-700 transition-all">
                        Guru Demo
                    </button>
                    <button type="button" onclick="fillDemoLogin('20357', 'Rahasia6#')" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold text-center border border-slate-200 dark:border-slate-700 transition-all">
                        Siswa Demo
                    </button>
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <div class="mt-6 text-center text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} <span class="font-heading font-semibold text-slate-600 dark:text-slate-300">SMK NEGERI 6 JAKARTA</span> — SIX-PRESENCE System
        </div>
    </div>

    <script>
        function fillDemoLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.querySelector('form').submit();
        }
        // Splash Screen Control: Show ONLY once when opening website for the first time in browser session
        window.addEventListener('DOMContentLoaded', () => {
            const progressBar = document.getElementById('splashProgressBar');
            const splashScreen = document.getElementById('splashScreen');

            // If already shown in this browser session (e.g. after login/logout), hide immediately
            if (sessionStorage.getItem('splashShown')) {
                if (splashScreen) splashScreen.style.display = 'none';
                return;
            }

            // Mark as shown for the current session
            sessionStorage.setItem('splashShown', 'true');

            setTimeout(() => {
                if (progressBar) progressBar.style.width = '100%';
            }, 100);

            setTimeout(() => {
                if (splashScreen) {
                    splashScreen.style.opacity = '0';
                    setTimeout(() => {
                        splashScreen.style.display = 'none';
                    }, 700);
                }
            }, 1400);
        });

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
