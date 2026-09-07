@extends('layouts.app')

@section('title', 'Riwayat Absensi - Absenam')

@section('header_title', 'Riwayat Absensi Siswa')
@section('header_subtitle', 'Pantau seluruh catatan kehadiran harian dan absensi sholat Anda.')

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="glass-card rounded-2xl p-4 border border-slate-200 dark:border-slate-800">
        <form action="{{ route('student.history') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="month" class="text-xs font-bold text-slate-700 dark:text-slate-300">Bulan:</label>
                <select name="month" id="month" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label for="year" class="text-xs font-bold text-slate-700 dark:text-slate-300">Tahun:</label>
                <select name="year" id="year" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs font-semibold">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="px-4 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-filter"></i> Filter Riwayat
            </button>
        </form>
    </div>

    <!-- Daily Attendance History Table -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-calendar-days text-brand-600"></i> Riwayat Absensi Harian
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Foto Selfie</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Lokasi / GPS</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-3 px-4">
                            <img src="{{ $row->photo }}" alt="Selfie" class="w-10 h-10 rounded-xl object-cover ring-2 ring-brand-500/20 cursor-pointer" onclick="Swal.fire({ imageUrl: '{{ $row->photo }}', imageAlt: 'Foto Absensi', showConfirmButton: false })">
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800 dark:text-slate-200">
                            <div>{{ date('d M Y', strtotime($row->date)) }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $row->time }} WIB</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400 max-w-[200px] truncate">
                            {{ $row->address }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                {{ $row->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 
                                   ($row->status == 'Terlambat' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 
                                   ($row->status == 'Ditolak' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300')) }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 italic">
                            {{ $row->teacher_notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Tidak ada riwayat absensi harian pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Prayer Attendance History Table -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-mosque text-emerald-600"></i> Riwayat Absensi Sholat Dzuhur
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Foto Selfie</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($prayerAttendances as $pRow)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-3 px-4">
                            <img src="{{ $pRow->photo }}" alt="Selfie" class="w-10 h-10 rounded-xl object-cover ring-2 ring-emerald-500/20 cursor-pointer" onclick="Swal.fire({ imageUrl: '{{ $pRow->photo }}', imageAlt: 'Foto Absensi Sholat', showConfirmButton: false })">
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800 dark:text-slate-200">
                            <div>{{ date('d M Y', strtotime($pRow->date)) }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $pRow->time }} WIB</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                            {{ $pRow->address }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold 
                                {{ $pRow->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' }}">
                                {{ $pRow->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 italic">
                            {{ $pRow->teacher_notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Tidak ada riwayat absensi sholat pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
