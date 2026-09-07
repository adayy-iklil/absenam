@extends('layouts.app')

@section('title', 'Dashboard Guru - SIX-PRESENCE SMKN 6 Jakarta')

@section('content')
<div class="space-y-6">

    @if($teacher && $teacher->teacherClassModel)
    <!-- Wali Kelas Badge Banner -->
    <div class="p-4 rounded-2xl bg-emerald-600 text-white shadow-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-100">Penugasan Resmi</span>
                <h3 class="text-lg font-black leading-tight">Wali Kelas {{ $teacher->teacherClassModel->name }}</h3>
            </div>
        </div>
        <a href="{{ route('teacher.attendance', ['filter' => 'wali_kelas']) }}" class="px-4 py-2 rounded-xl bg-white text-emerald-800 font-bold text-xs hover:bg-emerald-50 transition-colors shadow">
            Khusus Kelas {{ $teacher->teacherClassModel->name }} →
        </a>
    </div>
    @endif

    <!-- Teacher KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Hadir Hari Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-0.5 sm:mt-1">{{ $countHadir }}</h3>
                <p class="text-[10px] sm:text-[11px] text-emerald-500 font-semibold mt-1"><i class="fa-solid fa-circle-check"></i> Tervalidasi</p>
            </div>
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-lg sm:text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikonfirmasi</p>
                <h3 class="text-2xl sm:text-3xl font-black text-amber-500 mt-0.5 sm:mt-1">{{ $countPending }}</h3>
                <p class="text-[10px] sm:text-[11px] text-amber-500 font-semibold mt-1"><i class="fa-solid fa-clock-rotate-left"></i> Respon Guru</p>
            </div>
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-300 flex items-center justify-center text-lg sm:text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Terlambat</p>
                <h3 class="text-2xl sm:text-3xl font-black text-brand-600 mt-0.5 sm:mt-1">{{ $countTerlambat }}</h3>
                <p class="text-[10px] sm:text-[11px] text-brand-500 font-semibold mt-1"><i class="fa-solid fa-business-time"></i> > 07:00 WIB</p>
            </div>
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-brand-100 dark:bg-brand-950 text-brand-600 dark:text-brand-300 flex items-center justify-center text-lg sm:text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Alpha / Tanpa Ket.</p>
                <h3 class="text-2xl sm:text-3xl font-black text-rose-500 mt-0.5 sm:mt-1">{{ $countTidakHadir }}</h3>
                <p class="text-[10px] sm:text-[11px] text-rose-500 font-semibold mt-1"><i class="fa-solid fa-triangle-exclamation"></i> Belum Absen</p>
            </div>
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-300 flex items-center justify-center text-lg sm:text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
    </div>

    <!-- Attendance Chart Section -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-brand-600"></i> Grafik Kehadiran Siswa Mingguan
            </h3>
            <span class="text-xs text-slate-500">7 Hari Terakhir</span>
        </div>
        <div class="h-64">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    <!-- Pending Confirmation List with Watermark Photo View -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-user-check text-amber-500"></i> Antrean Absensi Perlu Konfirmasi Guru
                @if($teacher && $teacher->teacherClassModel)
                    <span class="text-xs font-bold text-emerald-600">(Kelas {{ $teacher->teacherClassModel->name }})</span>
                @endif
            </h3>
            <a href="{{ route('teacher.attendance') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase">
                        <th class="py-3 px-4">Foto Selfie</th>
                        <th class="py-3 px-4">Nama Siswa / NIS</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Timestamp & Waktu</th>
                        <th class="py-3 px-4">Nama Jalan Lokasi</th>
                        <th class="py-3 px-4 text-center">Aksi Konfirmasi Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($pendingAttendances as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                        <td class="py-3 px-4">
                            <img src="{{ $row->photo }}" alt="Selfie" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-brand-500/20 cursor-pointer hover:scale-105 transition-transform" onclick="openPhotoModal({{ json_encode([
                                'photoUrl' => $row->photo,
                                'name' => $row->student->name ?? ($row->student->user->name ?? '-'),
                                'nis' => $row->student->nis ?? '-',
                                'class' => $row->student->classModel->name ?? '-',
                                'date' => date('d M Y', strtotime($row->date)),
                                'time' => $row->time,
                                'address' => $row->address,
                                'device' => $row->device ?? 'Mobile Device',
                                'ip' => $row->ip_address ?? '127.0.0.1'
                            ]) }})">
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-extrabold text-slate-900 dark:text-white">{{ $row->student->name ?? ($row->student->user->name ?? '-') }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">NIS: {{ $row->student->nis ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                            {{ $row->student->classModel->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">
                            {{ $row->time }} WIB
                        </td>
                        <td class="py-3 px-4 max-w-[200px] truncate font-bold text-slate-700 dark:text-slate-300">
                            <i class="fa-solid fa-road text-brand-600"></i> {{ $row->address }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Hadir">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Terlambat">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20">
                                        <i class="fa-solid fa-clock"></i> Terlambat
                                    </button>
                                </form>
                                <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400">Semua absensi hari ini sudah dikonfirmasi!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- High-Res Photo Viewer Modal WITH WATERMARK CARD -->
<div id="photoViewModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
            <div>
                <h3 id="photoStudentName" class="font-black text-base text-slate-900 dark:text-white">Foto Selfie Absensi</h3>
                <p id="photoStudentSub" class="text-xs text-slate-500">Detail Verifikasi Guru SMKN 6 Jakarta</p>
            </div>
            <button onclick="document.getElementById('photoViewModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
        </div>

        <div class="relative rounded-2xl overflow-hidden mb-4 aspect-[4/3] bg-black">
            <img id="photoFullView" src="" alt="Full View" class="w-full h-full object-cover">
            
            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent p-4 text-white space-y-1">
                <div class="flex items-center justify-between text-xs font-bold">
                    <span id="wmTime" class="bg-brand-600 px-2 py-0.5 rounded font-mono text-white">07:00:00 WIB</span>
                    <span id="wmDate" class="text-slate-300">01 Jan 2026</span>
                </div>
                <p id="wmAddress" class="text-xs font-extrabold text-amber-300 leading-snug line-clamp-2">
                    <i class="fa-solid fa-road text-amber-400"></i> Jl. Mahakam No.2, Kebayoran Baru, Jakarta Selatan
                </p>
                <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                    <span id="wmDevice"><i class="fa-solid fa-mobile-screen"></i> Mobile Device</span>
                    <span id="wmIp"><i class="fa-solid fa-network-wired"></i> IP: 127.0.0.1</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end">
            <button onclick="document.getElementById('photoViewModal').classList.add('hidden')" class="px-5 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 font-bold text-xs">
                Tutup Preview
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyDays) !!},
            datasets: [
                { label: 'Hadir', data: {!! json_encode($weeklyHadir) !!}, backgroundColor: '#10b981', borderRadius: 8 },
                { label: 'Terlambat', data: {!! json_encode($weeklyTerlambat) !!}, backgroundColor: '#06b6d4', borderRadius: 8 },
                { label: 'Belum Konfirmasi', data: {!! json_encode($weeklyPending) !!}, backgroundColor: '#f59e0b', borderRadius: 8 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });

    function openPhotoModal(data) {
        document.getElementById('photoStudentName').innerText = data.name + ' (' + data.class + ')';
        document.getElementById('photoStudentSub').innerText = 'NIS: ' + data.nis;
        document.getElementById('photoFullView').src = data.photoUrl;

        document.getElementById('wmTime').innerText = data.time + ' WIB';
        document.getElementById('wmDate').innerText = data.date;
        document.getElementById('wmAddress').innerHTML = '<i class="fa-solid fa-road text-amber-400"></i> ' + data.address;
        document.getElementById('wmDevice').innerHTML = '<i class="fa-solid fa-mobile-screen"></i> ' + (data.device ? data.device.substring(0, 30) : 'Web Mobile');
        document.getElementById('wmIp').innerHTML = '<i class="fa-solid fa-network-wired"></i> IP: ' + data.ip;

        document.getElementById('photoViewModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
