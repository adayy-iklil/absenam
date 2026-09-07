@extends('layouts.app')

@section('title', 'Dashboard Siswa - SIX-PRESENCE SMKN 6 Jakarta')

@section('content')

@push('styles')
<style>
    /* Dynamic Shimmer Gradient Flow Animation */
    @keyframes gradientFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Hero Card Slide-up Entry Animation */
    @keyframes heroSlideUp {
        0% {
            opacity: 0;
            transform: translateY(24px) scale(0.97);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Avatar Glow Aura Breathing Pulse */
    @keyframes avatarGlowPulse {
        0%, 100% {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.4), 0 0 35px rgba(59, 130, 246, 0.6);
            border-color: rgba(255, 255, 255, 0.6);
        }
        50% {
            box-shadow: 0 0 28px rgba(255, 255, 255, 0.9), 0 0 50px rgba(147, 197, 253, 0.9);
            border-color: rgba(255, 255, 255, 1);
        }
    }

    /* Continuous 360-Degree Circular Rotating Text Animation */
    @keyframes rotateCircularText {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Floating Aesthetic Sparkle Particles */
    @keyframes sparkleFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
        50% { transform: translateY(-8px) rotate(15deg) scale(1.1); }
    }

    /* Shimmer Light Reflection Pass */
    @keyframes shimmerReflection {
        0% { transform: translateX(-100%) skewX(-15deg); }
        100% { transform: translateX(200%) skewX(-15deg); }
    }

    .hero-animated-card {
        background: linear-gradient(-45deg, #1e40af, #2563eb, #4f46e5, #3b82f6);
        background-size: 300% 300%;
        animation: gradientFlow 10s ease infinite, heroSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .avatar-glow {
        animation: avatarGlowPulse 3.5s ease-in-out infinite;
    }

    .animate-rotating-text {
        animation: rotateCircularText 14s linear infinite;
        transform-origin: center center;
    }

    .sparkle-float {
        animation: sparkleFloat 4s ease-in-out infinite;
    }

    .shimmer-light-bar {
        animation: shimmerReflection 6s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-6 mb-6">

    <!-- Aesthetic Animated Student Profile Hero Card (Container Nama Siswa - Mobile Optimized) -->
    <div class="hero-animated-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xl sm:shadow-2xl relative overflow-hidden text-white transition-all duration-300 hover:shadow-brand-500/20">
        
        <!-- Shimmering Light Beam Overlay -->
        <div class="absolute inset-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/10 to-transparent shimmer-light-bar pointer-events-none"></div>

        <!-- Glowing Orb Background Blurs -->
        <div class="absolute -right-10 -top-10 w-80 h-80 bg-white/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-10 w-60 h-60 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6">
            
            <!-- Left Student Info & Circular Avatar with 100% Even Text Ring (Nama, Kelas, SMKN 6) -->
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-6 text-center sm:text-left w-full md:w-auto">
                
                <!-- Circular Avatar Container with 100% Even Text Ring (Nama, Kelas, SMKN 6) -->
                <div class="relative w-24 h-24 sm:w-36 sm:h-36 flex items-center justify-center flex-shrink-0">
                    
                    @php
                        $studentNameUpper = strtoupper($student->name ?? $student->user->name);
                        $classNameUpper = strtoupper($student->classModel->name ?? '-');
                        $textToDisplay = $studentNameUpper . ' • KELAS ' . $classNameUpper . ' • SMKN 6 JAKARTA • ';
                        $totalLen = mb_strlen($textToDisplay);
                        
                        // Target circumference ~ 238.76 SVG units
                        // Dynamically balance font size and letter spacing to fit 100% evenly around the 360 circle ring with ZERO blank space
                        if ($totalLen > 0) {
                            $fontSize = number_format(max(4.0, min(7.5, 222 / $totalLen)), 2);
                            $letterSpacing = number_format(max(0.4, min(3.2, (238.76 - ($totalLen * $fontSize * 0.62)) / $totalLen)), 2);
                        } else {
                            $fontSize = "6.00";
                            $letterSpacing = "1.20";
                        }
                    @endphp

                    <!-- 100% Even Rotating Circular Text (Nama • Kelas • SMKN 6 Jakarta •) -->
                    <svg class="w-24 h-24 sm:w-36 sm:h-36 absolute inset-0 animate-rotating-text pointer-events-none z-0" viewBox="0 0 100 100">
                        <path id="circlePath" d="M 50, 50 m -38, 0 a 38,38 0 1,1 76,0 a 38,38 0 1,1 -76,0" fill="none"/>
                        <text fill="rgba(255, 255, 255, 0.95)" font-size="{{ $fontSize }}" font-weight="800" letter-spacing="{{ $letterSpacing }}">
                            <textPath href="#circlePath" startOffset="0%">
                                {{ $textToDisplay }}
                            </textPath>
                        </text>
                    </svg>

                    <!-- Center Circular Student Avatar / Initials Circle -->
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-white/20 border-2 border-white/80 p-0.5 sm:p-1 backdrop-blur-md avatar-glow flex items-center justify-center overflow-hidden z-10 transition-transform duration-300 hover:scale-110 shadow-xl">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name ?? $student->user->name) }}&background=2563eb&color=fff&size=128" alt="Profile" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>

                <div>
                    <!-- Gender specific badge: Siswa / Siswi -->
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3.5 sm:py-1 rounded-full bg-white/20 border border-white/30 backdrop-blur-md text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-white mb-1 sm:mb-1.5 sparkle-float">
                        <span>{{ ($student->gender ?? 'L') == 'P' ? 'Siswi Aktif' : 'Siswa Aktif' }}</span>
                    </div>

                    <!-- Animated Student Name -->
                    <h2 class="font-heading text-lg sm:text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-sm leading-tight">
                        {{ $student->name ?? $student->user->name }}
                    </h2>

                    <!-- Student Metadata Badges (No Icons) -->
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-2 sm:gap-x-4 gap-y-0.5 text-xs sm:text-sm text-white/90 mt-1 sm:mt-1.5">
                        <span>NIS: <strong>{{ $student->nis }}</strong></span>
                        <span class="hidden sm:inline">•</span>
                        <span>Kelas: <strong>{{ $student->classModel->name ?? '-' }}</strong></span>
                        <span class="hidden sm:inline">•</span>
                        <span>Jurusan: <strong>{{ $student->department->name ?? '-' }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Right Live Clock Card -->
            <div class="bg-white/15 backdrop-blur-md border border-white/25 rounded-xl sm:rounded-2xl px-4 py-2.5 sm:px-6 sm:py-4 text-center sm:text-right w-full sm:w-auto min-w-0 sm:min-w-[200px] shadow-lg">
                <div class="text-[10px] sm:text-xs uppercase tracking-wider text-white/80 font-medium mb-0.5 sm:mb-1">
                    <i class="fa-regular fa-clock"></i> <span id="currentDateStr">{{ date('l, d F Y') }}</span>
                </div>
                <div id="liveClock" class="text-xl sm:text-3xl md:text-4xl font-semibold font-mono tracking-tight text-white">
                    {{ date('H:i:s') }} WIB
                </div>
            </div>

        </div>
    </div>

    <!-- STATISTICAL SUMMARY COUNTERS WITH TERLAMBAT INCLUDED -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Hadir</p>
                <p class="font-heading text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">{{ $countHadir }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-950 text-cyan-600 dark:text-cyan-300 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Terlambat</p>
                <p class="font-heading text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">{{ $countTerlambat }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-300 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Izin</p>
                <p class="font-heading text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">{{ $countIzin }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-300 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-notes-medical"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Sakit</p>
                <p class="font-heading text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">{{ $countSakit }}</p>
            </div>
        </div>

        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-slate-900 rounded-2xl p-3.5 sm:p-4 border border-slate-200 dark:border-slate-800 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-300 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Alpha</p>
                <p class="font-heading text-lg sm:text-xl font-semibold text-slate-900 dark:text-white">{{ $countAlpha }}</p>
            </div>
        </div>
    </div>

    <!-- Attendance Action Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- 1. DAILY ATTENDANCE CARD -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/50 text-brand-600 dark:text-brand-300 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold text-lg text-slate-900 dark:text-white">Absensi Harian</h3>
                            <p class="text-xs text-slate-500">Batas Toleransi Masuk: 07:00 WIB</p>
                        </div>
                    </div>
                    @if($todayAttendance)
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold 
                            {{ $todayAttendance->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 
                               ($todayAttendance->status == 'Terlambat' ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-950 dark:text-cyan-300' : 
                               ($todayAttendance->status == 'Sakit' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 
                               ($todayAttendance->status == 'Izin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300'))) }}">
                            {{ $todayAttendance->status }}
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            Belum Absen
                        </span>
                    @endif
                </div>

                @if($todayAttendance)
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl space-y-2 text-xs">
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Status Kehadiran:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $todayAttendance->status }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Waktu Simpan:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $todayAttendance->time }} WIB</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500"><i class="fa-solid fa-road text-brand-600"></i> Nama Jalan:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[220px]">{{ $todayAttendance->address }}</span>
                        </div>
                        @if($todayAttendance->teacher_notes)
                        <div class="flex justify-between pt-1">
                            <span class="text-slate-500">Catatan Alasan:</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300 italic">{{ $todayAttendance->teacher_notes }}</span>
                        </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
                        Pilih status kehadiran Anda (Hadir, Sakit, Izin, atau Alpha) lalu sertakan foto selfie atau foto surat bukti keterangan.
                    </p>
                @endif
            </div>

            <div class="mt-6">
                @if(!$todayAttendance)
                    <button onclick="openCameraModal('daily')" class="w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-xl shadow-brand-500/25 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                        Isi Absensi Sekarang
                    </button>
                @else
                    <button disabled class="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                        Absen Harian Selesai
                    </button>
                @endif
            </div>
        </div>

        <!-- 2. PRAYER ATTENDANCE CARD -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-mosque"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold text-lg text-slate-900 dark:text-white">Absensi Sholat Dzuhur</h3>
                            <p class="text-xs text-slate-500">Jadwal Masjid: 11:45 - 12:30 WIB</p>
                        </div>
                    </div>
                    @if($todayPrayerAttendance)
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                            {{ $todayPrayerAttendance->status }}
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                            Status Sholat
                        </span>
                    @endif
                </div>

                @if($todayPrayerAttendance)
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl space-y-2 text-xs">
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Waktu Absen Sholat:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $todayPrayerAttendance->time }} WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status Konfirmasi:</span>
                            <span class="font-bold text-emerald-600">{{ $todayPrayerAttendance->status }}</span>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-slate-100 dark:bg-slate-800/70 rounded-2xl text-xs text-slate-600 dark:text-slate-300 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-brand-600"></i>
                        <span>{{ $prayerStatusMsg }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                @if(!$todayPrayerAttendance)
                    @if($canDoPrayer)
                        <button onclick="openCameraModal('prayer')" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-xl shadow-emerald-500/25 flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                            Absen Sholat (Selfie)
                        </button>
                    @else
                        <button disabled class="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                            {{ $prayerStatusMsg }}
                        </button>
                    @endif
                @else
                    <button disabled class="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                        Absen Sholat Selesai
                    </button>
                @endif
            </div>
        </div>

    </div>

    <!-- MONTHLY ATTENDANCE CALENDAR MATRIX GRID -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 space-y-4 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h3 class="font-heading font-semibold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-brand-600"></i> Absensi Bulan Ini ({{ date('F Y') }})
                </h3>
                <p class="text-xs text-slate-500">Matriks kalender bulanan kehadiran Anda.</p>
            </div>
            
            <!-- Color Indicator Legend -->
            <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">● Hadir</span>
                <span class="px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-700 border border-cyan-300">● Terlambat</span>
                <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 border border-blue-300">● Izin</span>
                <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 border border-amber-300">● Sakit</span>
                <span class="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 border border-rose-300">● Alpha</span>
                <span class="px-2.5 py-1 rounded-lg bg-slate-200 text-slate-600 border border-slate-300">● Libur (Sabtu/Minggu)</span>
            </div>
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-4 sm:grid-cols-7 lg:grid-cols-10 gap-2.5">
            @foreach($calendarDays as $cDay)
            <div class="p-3 rounded-2xl text-center border transition-transform hover:scale-105
                {{ $cDay['is_weekend'] ? 'bg-slate-100 text-slate-400 border-slate-200 dark:bg-slate-900/40 dark:border-slate-800' :
                   ($cDay['status'] == 'Hadir' ? 'bg-emerald-500 text-white font-bold border-emerald-600 shadow-md shadow-emerald-500/20' :
                   ($cDay['status'] == 'Terlambat' ? 'bg-cyan-500 text-white font-bold border-cyan-600 shadow-md shadow-cyan-500/20' :
                   ($cDay['status'] == 'Izin' ? 'bg-blue-500 text-white font-bold border-blue-600 shadow-md shadow-blue-500/20' :
                   ($cDay['status'] == 'Sakit' ? 'bg-amber-500 text-white font-bold border-amber-600 shadow-md shadow-amber-500/20' :
                   ($cDay['status'] == 'Belum/Alpha' ? 'bg-rose-500 text-white font-bold border-rose-600 shadow-md shadow-rose-500/20' :
                   'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800'))))) }}">
                <span class="text-[10px] uppercase font-medium block opacity-80">{{ $cDay['day_name'] }}</span>
                <span class="text-base font-semibold block leading-none my-1">{{ $cDay['day'] }}</span>
                <span class="text-[10px] font-medium truncate block">
                    {{ $cDay['is_weekend'] ? 'Libur' : $cDay['status'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ANNOUNCEMENTS BOARD -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bullhorn text-brand-600"></i> Pengumuman Sekolah SMKN 6 Jakarta
        </h3>
        <div class="space-y-3">
            @forelse($announcements as $ann)
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-semibold text-sm text-slate-900 dark:text-white">{{ $ann->title }}</h4>
                        <span class="text-xs text-slate-400">{{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300">{{ $ann->content }}</p>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada pengumuman hari ini.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- WEBCAM SELFIE & STATUS SELECTION MODAL (HADIR, SAKIT, IZIN, ALPHA) -->
<div id="cameraModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 relative max-h-[90vh] overflow-y-auto">
        
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 id="cameraModalTitle" class="font-heading font-semibold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                Form Absensi & Selfie
            </h3>
            <button onclick="closeCameraModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <!-- STATUS SELECTION BUTTONS -->
        <div id="statusSelectionContainer" class="mb-4">
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Status Kehadiran:</label>
            <div class="grid grid-cols-4 gap-2">
                <button type="button" onclick="selectStatus('Hadir')" id="btnStatusHadir" class="status-btn py-2 px-1 rounded-xl border-2 border-emerald-500 bg-emerald-100 text-emerald-800 font-bold text-xs text-center transition-all shadow-sm">
                    🟢 Hadir
                </button>
                <button type="button" onclick="selectStatus('Sakit')" id="btnStatusSakit" class="status-btn py-2 px-1 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold text-center transition-all">
                    🟡 Sakit
                </button>
                <button type="button" onclick="selectStatus('Izin')" id="btnStatusIzin" class="status-btn py-2 px-1 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold text-center transition-all">
                    🔵 Izin
                </button>
                <button type="button" onclick="selectStatus('Alpha')" id="btnStatusAlpha" class="status-btn py-2 px-1 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold text-center transition-all">
                    🔴 Alpha
                </button>
            </div>
            <input type="hidden" id="selectedStatusValue" value="Hadir">
        </div>

        <!-- Optional Notes Input -->
        <div id="notesContainer" class="mb-4">
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan / Alasan (Opsional):</label>
            <input type="text" id="attendanceNotes" placeholder="Misal: Sakit demam, Izin ada acara keluarga..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white">
        </div>

        <!-- Camera Live Feed / Canvas -->
        <div class="relative bg-black rounded-2xl overflow-hidden aspect-[4/3] mb-4 flex items-center justify-center">
            <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
            <canvas id="photoCanvas" class="hidden w-full h-full object-cover"></canvas>
            
            <div id="faceOverlay" class="absolute inset-0 border-4 border-dashed border-white/40 rounded-full m-8 pointer-events-none flex items-center justify-center">
                <span class="text-white/80 text-xs font-medium bg-black/50 px-3 py-1.5 rounded-full backdrop-blur-sm">Posisikan Wajah / Bukti di Tengah Frame</span>
            </div>
        </div>

        <!-- Location Status Indicator with Street Name -->
        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl text-xs space-y-1.5 mb-4 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between text-slate-700 dark:text-slate-200">
                <span class="font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-road text-brand-600"></i> Nama Jalan Lokasi GPS:
                </span>
                <span id="gpsStatus" class="font-semibold text-amber-500">Mendeteksi Nama Jalan...</span>
            </div>
            <p id="gpsAddressText" class="text-xs font-medium text-brand-600 dark:text-brand-400 bg-white dark:bg-slate-900 p-2 rounded-xl border border-brand-200 dark:border-brand-900 leading-snug">
                Mencari alamat jalan lokasi Anda melalui satelit GPS...
            </p>
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center gap-3">
            <button id="snapBtn" onclick="takeSnapshot()" class="flex-1 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-lg shadow-brand-500/30 flex items-center justify-center gap-2">
                Ambil Foto / Bukti
            </button>
            <button id="retakeBtn" onclick="retakePhoto()" class="hidden flex-1 py-3.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-sm flex items-center justify-center gap-2">
                Foto Ulang
            </button>
            <button id="submitBtn" onclick="submitAttendance()" class="hidden flex-1 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2">
                Kirim Absensi
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    setInterval(() => {
        const now = new Date();
        document.getElementById('liveClock').innerText = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
    }, 1000);

    let currentAttendanceType = 'daily';
    let mediaStream = null;
    let capturedPhotoBase64 = null;
    let userLat = null;
    let userLng = null;
    let userAddress = "Jl. Mahakam No.2, Kramat Pela, Kebayoran Baru, Jakarta Selatan (SMKN 6 Jakarta)";

    function selectStatus(status) {
        document.getElementById('selectedStatusValue').value = status;
        
        // Reset button styles
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.className = "status-btn py-2 px-1 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-semibold text-center transition-all";
        });

        const activeBtn = document.getElementById('btnStatus' + status);
        if (activeBtn) {
            if (status === 'Hadir') {
                activeBtn.className = "status-btn py-2 px-1 rounded-xl border-2 border-emerald-500 bg-emerald-100 text-emerald-800 font-bold text-xs text-center transition-all shadow-sm";
            } else if (status === 'Sakit') {
                activeBtn.className = "status-btn py-2 px-1 rounded-xl border-2 border-amber-500 bg-amber-100 text-amber-800 font-bold text-xs text-center transition-all shadow-sm";
            } else if (status === 'Izin') {
                activeBtn.className = "status-btn py-2 px-1 rounded-xl border-2 border-blue-500 bg-blue-100 text-blue-800 font-bold text-xs text-center transition-all shadow-sm";
            } else if (status === 'Alpha') {
                activeBtn.className = "status-btn py-2 px-1 rounded-xl border-2 border-rose-500 bg-rose-100 text-rose-800 font-bold text-xs text-center transition-all shadow-sm";
            }
        }
    }

    function openCameraModal(type) {
        currentAttendanceType = type;
        document.getElementById('cameraModalTitle').innerText = type === 'daily' ? 'Form Absensi Harian (Hadir, Sakit, Izin, Alpha)' : 'Ambil Foto Selfie Absensi Sholat';
        
        if (type === 'prayer') {
            document.getElementById('statusSelectionContainer').classList.add('hidden');
            document.getElementById('notesContainer').classList.add('hidden');
        } else {
            document.getElementById('statusSelectionContainer').classList.remove('hidden');
            document.getElementById('notesContainer').classList.remove('hidden');
        }

        document.getElementById('cameraModal').classList.remove('hidden');
        
        startWebcam();
        getGeolocation();
    }

    function closeCameraModal() {
        stopWebcam();
        document.getElementById('cameraModal').classList.add('hidden');
        retakePhoto();
    }

    function startWebcam() {
        const video = document.getElementById('webcamVideo');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false })
            .then(stream => {
                mediaStream = stream;
                video.srcObject = stream;
            })
            .catch(err => {
                Swal.fire('Kamera Tidak Ditemukan', 'Harap izinkan akses kamera pada perangkat Anda.', 'error');
                closeCameraModal();
            });
    }

    function stopWebcam() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
    }

    // Real Reverse Geocoding via Nominatim OpenStreetMap API to fetch exact Street Name
    function getGeolocation() {
        const gpsStatus = document.getElementById('gpsStatus');
        const gpsAddressText = document.getElementById('gpsAddressText');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    
                    gpsStatus.innerText = "GPS Terhubung ✓";
                    gpsStatus.className = "font-bold text-emerald-500";

                    // Fetch Street Name from Nominatim OpenStreetMap API
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.display_name) {
                                userAddress = data.display_name;
                                gpsAddressText.innerText = userAddress;
                            } else {
                                userAddress = `Jl. Mahakam No.2, Jakarta Selatan (Lat: ${userLat.toFixed(4)}, Lng: ${userLng.toFixed(4)})`;
                                gpsAddressText.innerText = userAddress;
                            }
                        })
                        .catch(err => {
                            userAddress = `Jl. Mahakam No.2, Jakarta Selatan (SMKN 6 Jakarta)`;
                            gpsAddressText.innerText = userAddress;
                        });
                },
                (error) => {
                    gpsStatus.innerText = "GPS Default Sekolah";
                    gpsStatus.className = "font-bold text-amber-500";
                    userAddress = "Jl. Mahakam No.2, Kramat Pela, Kebayoran Baru, Jakarta Selatan (SMKN 6 Jakarta)";
                    gpsAddressText.innerText = userAddress;
                }
            );
        }
    }

    function takeSnapshot() {
        const video = document.getElementById('webcamVideo');
        const canvas = document.getElementById('photoCanvas');
        const context = canvas.getContext('2d');

        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;

        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        capturedPhotoBase64 = canvas.toDataURL('image/jpeg', 0.85);

        video.classList.add('hidden');
        canvas.classList.remove('hidden');
        document.getElementById('faceOverlay').classList.add('hidden');

        document.getElementById('snapBtn').classList.add('hidden');
        document.getElementById('retakeBtn').classList.remove('hidden');
        document.getElementById('submitBtn').classList.remove('hidden');
    }

    function retakePhoto() {
        const video = document.getElementById('webcamVideo');
        const canvas = document.getElementById('photoCanvas');

        canvas.classList.add('hidden');
        video.classList.remove('hidden');
        document.getElementById('faceOverlay').classList.remove('hidden');

        document.getElementById('snapBtn').classList.remove('hidden');
        document.getElementById('retakeBtn').classList.add('hidden');
        document.getElementById('submitBtn').classList.add('hidden');
        capturedPhotoBase64 = null;
    }

    function submitAttendance() {
        if (!capturedPhotoBase64) {
            Swal.fire('Foto Wajib', 'Ambil foto selfie atau bukti terlebih dahulu!', 'warning');
            return;
        }

        const selectedStatus = document.getElementById('selectedStatusValue').value;
        const notes = document.getElementById('attendanceNotes').value;
        const endpoint = currentAttendanceType === 'daily' ? '{{ route("student.attendance.store") }}' : '{{ route("student.prayer.store") }}';

        Swal.fire({
            title: 'Mengirimkan Absensi...',
            text: 'Menyimpan status (' + selectedStatus + ') & lokasi jalan Anda',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                photo: capturedPhotoBase64,
                status: selectedStatus,
                notes: notes,
                latitude: userLat,
                longitude: userLng,
                address: userAddress,
                browser_name: navigator.userAgent
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Absensi Berhasil!', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal Absen', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Terjadi kesalahan sistem saat menyimpan absensi.', 'error');
        });
    }
</script>
@endpush
