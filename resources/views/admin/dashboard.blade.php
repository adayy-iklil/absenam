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

        <!-- Chart 1: Attendance by Department (Responsive & Mobile-Optimized) -->
        <div class="glass-card rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-brand-600"></i> Absensi Hari Ini per Jurusan
                    </h3>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
                        {{ array_sum($deptCounts) }} Hadir
                    </span>
                </div>

                <!-- Donut Chart Container -->
                <div class="relative h-48 sm:h-56 w-full flex items-center justify-center my-1">
                    <canvas id="deptChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ array_sum($deptCounts) }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase mt-0.5 tracking-wider">Total Hadir</span>
                    </div>
                </div>
            </div>

            <!-- Department Breakdown Cards (Clean, Readable on Mobile) -->
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                    Rincian Kehadiran per Jurusan:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($deptStats as $ds)
                    <div class="p-2.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0 shadow-sm" style="background-color: {{ $ds['color'] }};"></span>
                            <div class="min-w-0">
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $ds['code'] }}</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block truncate max-w-[140px] sm:max-w-[110px]">{{ $ds['name'] }}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-extrabold text-xs text-slate-900 dark:text-white">
                                {{ $ds['attended'] }} <span class="text-[10px] font-normal text-slate-400">/ {{ $ds['total'] }}</span>
                            </div>
                            <span class="text-[10px] font-semibold text-brand-600 dark:text-brand-400">
                                {{ $ds['percentage'] }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
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
    const deptLabels = {!! json_encode($deptLabels) !!};
    const deptCounts = {!! json_encode($deptCounts) !!};
    const deptColors = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#f97316'];
    const totalDeptAttendance = deptCounts.reduce((a, b) => a + b, 0);

    const chartData = totalDeptAttendance > 0 ? deptCounts : deptLabels.map(() => 1);
    const chartColors = totalDeptAttendance > 0 ? deptColors : deptLabels.map(() => '#cbd5e1');

    new Chart(document.getElementById('deptChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: deptLabels,
            datasets: [{
                data: chartData,
                backgroundColor: chartColors,
                borderWidth: 2,
                borderColor: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (totalDeptAttendance === 0) return ' Belum ada data absensi hari ini';
                            const val = deptCounts[context.dataIndex] || 0;
                            return ` ${context.label}: ${val} Siswa Hadir`;
                        }
                    }
                }
            },
            cutout: '72%'
        }
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
