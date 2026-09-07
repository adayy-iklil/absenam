@extends('layouts.app')

@section('title', 'Laporan Absensi - Absenam SMKN 6 Jakarta')

@section('header_title', 'Laporan Rekapitulasi Absensi')
@section('header_subtitle', 'Filter dan export seluruh riwayat kehadiran siswa SMKN 6 Jakarta ke format PDF, Excel, atau Print.')

@section('header_action')
<a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-300 flex items-center gap-2 transition-colors">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
</a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Form & Export Buttons Card -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <form action="{{ route('admin.reports') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-semibold">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-semibold">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kelas</label>
                    <select name="class_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-semibold">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Status Kehadiran</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-semibold">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>

                <!-- Export Buttons Group -->
                <div class="flex items-center gap-2">
                    <button type="submit" name="export" value="excel" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center gap-1.5">
                        <i class="fa-solid fa-file-excel"></i> Export Excel (.CSV)
                    </button>

                    <button type="submit" name="export" value="print" target="_blank" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20 flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF / Print
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Reports Data Table -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Tanggal & Jam</th>
                        <th class="py-3 px-4">NIS</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas / Jurusan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Alamat / Nama Jalan</th>
                        <th class="py-3 px-4">Verifikator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $index => $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-medium">
                            <div>{{ date('d M Y', strtotime($row->date)) }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $row->time }} WIB</div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-brand-600">{{ $row->student->nis ?? '-' }}</td>
                        <td class="py-3 px-4 font-extrabold text-slate-900 dark:text-white">{{ $row->student->name ?? ($row->student->user->name ?? '-') }}</td>
                        <td class="py-3 px-4">
                            <span class="font-bold">{{ $row->student->classModel->name ?? '-' }}</span>
                            <span class="text-[11px] text-slate-400">({{ $row->student->department->code ?? '-' }})</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold 
                                {{ $row->status == 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 
                                   ($row->status == 'Terlambat' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300') }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 max-w-[200px] truncate">{{ $row->address }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $row->teacher->user->name ?? 'System Auto' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-slate-400">Tidak ada data absensi untuk laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
