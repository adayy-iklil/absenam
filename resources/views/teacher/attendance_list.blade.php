@extends('layouts.app')

@section('title', 'Daftar Absensi Siswa - Absenam SMKN 6 Jakarta')

@section('header_title', 'Konfirmasi Absensi Siswa')
@section('header_subtitle', 'Verifikasi foto selfie, timestamp waktu, dan nama jalan lokasi GPS siswa SMKN 6 Jakarta.')

@section('content')
<div class="space-y-6">

    @if(!$teacher || !$teacher->teacher_class_id)
    <!-- Warning Banner for Non-Wali Kelas Teachers -->
    <div class="p-4 rounded-2xl bg-amber-500 text-white shadow-lg flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        <div>
            <h4 class="font-extrabold text-sm">Hanya Wali Kelas yang Berhak Mengonfirmasi Absensi</h4>
            <p class="text-xs text-amber-100 mt-0.5">Anda saat ini terdaftar sebagai Pengajar Umum (Bukan Wali Kelas). Hubungi Administrator jika Anda adalah Wali Kelas.</p>
        </div>
    </div>
    @else
    <!-- Wali Kelas Badge Banner -->
    <div class="p-4 rounded-2xl bg-emerald-600 text-white shadow-md flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-user-shield text-2xl"></i>
            <div>
                <span class="text-xs uppercase font-extrabold text-emerald-200">Hak Akses Konfirmasi Aktif</span>
                <h4 class="font-black text-base">Wali Kelas {{ $teacher->teacherClassModel->name ?? '-' }}</h4>
            </div>
        </div>
        <span class="text-xs font-bold bg-white/20 px-3 py-1 rounded-full">SMK NEGERI 6 JAKARTA</span>
    </div>
    @endif

    <!-- Filters & Search Bar -->
    <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
        <form action="{{ route('teacher.attendance') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <div>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
            </div>

            <div>
                <select name="class_id" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Konfirmasi" {{ request('status') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Nama Siswa..." class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-magnifying-glass"></i> Cari / Filter
            </button>
        </form>
    </div>

    <!-- Attendance Table Card -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Foto Selfie</th>
                        <th class="py-3.5 px-4">Nama Siswa / NIS</th>
                        <th class="py-3.5 px-4">Kelas & Jurusan</th>
                        <th class="py-3.5 px-4">Timestamp Waktu</th>
                        <th class="py-3.5 px-4">Nama Jalan (Alamat GPS)</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Konfirmasi Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $row)
                    @php
                        $isWaliKelasForThisStudent = $teacher && $teacher->teacher_class_id && ($row->student->class_id == $teacher->teacher_class_id);
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        
                        <!-- Photo Thumbnail with Watermark Click View -->
                        <td class="py-3 px-4">
                            <div class="relative group cursor-pointer" onclick="openPhotoModal({{ json_encode([
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
                                <img src="{{ $row->photo }}" alt="Selfie" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-brand-500/20 shadow-md group-hover:scale-105 transition-transform">
                                <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold">
                                    <i class="fa-solid fa-expand"></i>
                                </div>
                            </div>
                        </td>

                        <!-- Student Info -->
                        <td class="py-3 px-4">
                            <div class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $row->student->name ?? ($row->student->user->name ?? '-') }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">NIS: {{ $row->student->nis ?? '-' }}</div>
                        </td>

                        <!-- Class & Dept -->
                        <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                            <div>{{ $row->student->classModel->name ?? '-' }}</div>
                            <div class="text-[11px] text-brand-600 dark:text-brand-400 font-bold">{{ $row->student->department->code ?? '-' }}</div>
                        </td>

                        <!-- Timestamp Date & Time -->
                        <td class="py-3 px-4">
                            <div class="font-black text-slate-900 dark:text-white font-mono text-sm">{{ $row->time }} WIB</div>
                            <div class="text-[11px] text-slate-400 font-semibold"><i class="fa-solid fa-clock text-amber-500"></i> {{ date('d M Y', strtotime($row->date)) }}</div>
                        </td>

                        <!-- Street Name & GPS Address -->
                        <td class="py-3 px-4 max-w-[220px]">
                            <p class="truncate text-slate-800 dark:text-slate-200 font-bold leading-tight" title="{{ $row->address }}">
                                <i class="fa-solid fa-road text-brand-600"></i> {{ $row->address }}
                            </p>
                            @if($row->latitude && $row->longitude)
                                <button onclick="openMapModal({{ $row->latitude }}, {{ $row->longitude }}, '{{ $row->student->name ?? ($row->student->user->name ?? '') }}')" class="text-[11px] font-bold text-brand-600 hover:underline flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-map-location-dot"></i> Peta Maps
                                </button>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold shadow-sm
                                {{ $row->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 
                                   ($row->status == 'Terlambat' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 
                                   ($row->status == 'Ditolak' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300')) }}">
                                {{ $row->status }}
                            </span>
                        </td>

                        <!-- Action Buttons (Restricted to Wali Kelas of that Student's Class) -->
                        <td class="py-3 px-4 text-center">
                            @if($isWaliKelasForThisStudent)
                                <div class="flex items-center justify-center gap-1.5">
                                    <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Hadir">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20" title="Setujui (Hadir)">
                                            <i class="fa-solid fa-check"></i> Setuju
                                        </button>
                                    </form>

                                    <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Terlambat">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20" title="Tandai Terlambat">
                                            <i class="fa-solid fa-clock"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('teacher.attendance.confirm', $row->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20" title="Tolak Absensi">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold text-[10px] inline-block" title="Hanya Wali Kelas {{ $row->student->classModel->name ?? '' }} yang berhak mengonfirmasi">
                                    <i class="fa-solid fa-lock"></i> Khusus Wali {{ $row->student->classModel->name ?? '' }}
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 font-medium">Tidak ada data absensi yang sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>

</div>

<!-- High-Res Photo Viewer Modal WITH STREET ADDRESS & TIMESTAMP WATERMARK CARD -->
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

<!-- Leaflet Map Modal -->
<div id="mapModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
            <h3 id="mapStudentName" class="font-extrabold text-sm text-slate-900 dark:text-white"><i class="fa-solid fa-map-location-dot text-brand-600"></i> Lokasi GPS Absensi</h3>
            <button onclick="document.getElementById('mapModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
        </div>
        <div id="mapContainer" class="w-full h-80 rounded-2xl"></div>
    </div>
</div>

@push('scripts')
<script>
    let mapInstance = null;

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

    function openMapModal(lat, lng, name) {
        document.getElementById('mapStudentName').innerText = 'Lokasi GPS: ' + name;
        document.getElementById('mapModal').classList.remove('hidden');

        setTimeout(() => {
            if (mapInstance) {
                mapInstance.remove();
            }
            mapInstance = L.map('mapContainer').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapInstance);

            L.marker([lat, lng]).addTo(mapInstance)
                .bindPopup(`<b>${name}</b><br>Lokasi Absensi Selfie`)
                .openPopup();
        }, 200);
    }
</script>
@endpush
@endsection
