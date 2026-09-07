@extends('layouts.app')

@section('title', 'Dashboard Admin - SIX-PRESENCE SMKN 6 Jakarta')

@section('content')
<div class="space-y-6">

    <!-- Top Executive Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Total Siswa</p>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalStudents }}</h3>
            <span class="text-[10px] text-brand-600 font-semibold"><i class="fa-solid fa-users"></i> Terdaftar</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Total Guru</p>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalTeachers }}</h3>
            <span class="text-[10px] text-emerald-600 font-semibold"><i class="fa-solid fa-chalkboard-user"></i> Pengajar</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Hadir Hari Ini</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $countHadir }}</h3>
            <span class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-circle-check"></i> Tervalidasi</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Terlambat</p>
            <h3 class="text-2xl font-black text-amber-500 mt-1">{{ $countTerlambat }}</h3>
            <span class="text-[10px] text-amber-500 font-semibold"><i class="fa-solid fa-clock"></i> Lewat Jam</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Izin / Sakit</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $countIzin + $countSakit }}</h3>
            <span class="text-[10px] text-blue-500 font-semibold"><i class="fa-solid fa-notes-medical"></i> Keterangan</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Alpha</p>
            <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $countAlpha }}</h3>
            <span class="text-[10px] text-rose-500 font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> Tanpa Ket.</span>
        </div>

    </div>

    <!-- Analytics Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart 1: Attendance by Department -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-brand-600"></i> Absensi Hari Ini per Jurusan
            </h3>
            <div class="h-64">
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Monthly Attendance Trend -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-area text-emerald-600"></i> Tren Kehadiran Bulanan (Tahunan)
            </h3>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Master Data Management Links -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-sliders text-brand-600"></i> Manajemen Data Master & Laporan
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('admin.students') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-950 text-brand-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Data Siswa</h4>
                        <p class="text-xs text-slate-500">Kelola {{ $totalStudents }} Siswa SMKN 6</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('admin.teachers') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Data Guru</h4>
                        <p class="text-xs text-slate-500">Kelola {{ $totalTeachers }} Guru Pengajar</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('admin.classes') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-indigo-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Kelas & Jurusan</h4>
                        <p class="text-xs text-slate-500">Kelola Tingkat & Program</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('admin.schedules') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-amber-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Jam Absensi & Sholat</h4>
                        <p class="text-xs text-slate-500">Atur Jam Masuk & Sholat</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('admin.announcements') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-rose-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Pengumuman Sekolah</h4>
                        <p class="text-xs text-slate-500">Terbitkan Informasi</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="{{ route('admin.reports') }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Laporan & Export</h4>
                        <p class="text-xs text-slate-500">Export PDF & Excel CSV</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>

        </div>
    </div>

</div>

@push('scripts')
<script>
    new Chart(document.getElementById('deptChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptLabels) !!},
            datasets: [{
                data: {!! json_encode($deptCounts) !!},
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Total Kehadiran',
                data: {!! json_encode($monthlyHadir) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endpush
@endsection
