@extends('layouts.app')

@section('title', 'Konfirmasi Absensi Sholat - Absenam')

@section('header_title', 'Konfirmasi Absensi Sholat Dzuhur')
@section('header_subtitle', 'Verifikasi bukti kehadiran sholat berjamaah siswa di musholla/masjid sekolah.')

@section('content')
<div class="space-y-6">

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
        <form action="{{ route('teacher.prayer') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
            </div>

            <div>
                <select name="status" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Konfirmasi" {{ request('status') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-500/20">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Foto Selfie Sholat</th>
                        <th class="py-3.5 px-4">Nama Siswa / NIS</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">Jam Absen</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Konfirmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($prayerAttendances as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                        <td class="py-3 px-4">
                            <img src="{{ $row->photo }}" alt="Selfie Sholat" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-emerald-500/20 shadow-md cursor-pointer hover:scale-105 transition-transform" onclick="Swal.fire({ imageUrl: '{{ $row->photo }}', imageAlt: 'Foto Absensi Sholat', showConfirmButton: false })">
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                            <div>{{ $row->student->user->name ?? '-' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">NIS: {{ $row->student->nis ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                            {{ $row->student->classModel->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">
                            {{ $row->time }} WIB
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold 
                                {{ $row->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('teacher.prayer.confirm', $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Hadir">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </button>
                                </form>
                                <form action="{{ route('teacher.prayer.confirm', $row->id) }}" method="POST" class="inline">
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
                        <td colspan="6" class="text-center py-6 text-slate-400">Tidak ada data absensi sholat untuk dikonfirmasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $prayerAttendances->links() }}
        </div>
    </div>

</div>
@endsection
