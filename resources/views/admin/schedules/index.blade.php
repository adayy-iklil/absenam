@extends('layouts.app')

@section('title', 'Manajemen Jam Absensi & Sholat - Absenam SMKN 6 Jakarta')

@section('header_title', 'Pengaturan Jam Absensi & Jadwal Sholat')
@section('header_subtitle', 'Tentukan batasan waktu absensi harian dan jendela absensi sholat Dzuhur SMKN 6 Jakarta.')

@section('header_action')
<a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-300 flex items-center gap-2 transition-colors">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- 1. Daily Attendance Schedule -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock text-brand-600"></i> Jam Masuk & Batas Toleransi Absensi Harian
        </h3>

        @foreach($schedules as $sch)
        <form action="{{ route('admin.schedules.update', $sch->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Nama Jadwal</label>
                <input type="text" value="{{ $sch->name }}" readonly class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Jam Pembukaan Absen</label>
                    <input type="time" name="start_time" value="{{ $sch->start_time }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-mono font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Jam Penutupan Absen</label>
                    <input type="time" name="end_time" value="{{ $sch->end_time }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-mono font-bold">
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1 text-amber-600">Batas Toleransi Keterlambatan (Jam Terlambat)</label>
                <input type="time" name="late_time" value="{{ $sch->late_time }}" required class="w-full px-3 py-2 rounded-xl border border-amber-300 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/40 font-mono font-bold text-amber-700 dark:text-amber-300">
                <p class="text-[11px] text-slate-400 mt-1">Siswa yang melakukan absensi setelah jam ini akan berstatus "Terlambat".</p>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold">Perbarui Jadwal Absen Harian</button>
        </form>
        @endforeach
    </div>

    <!-- 2. Prayer Attendance Schedule -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-mosque text-emerald-600"></i> Jendela Waktu Absensi Sholat Dzuhur
        </h3>

        @foreach($prayerSchedules as $psch)
        <form action="{{ route('admin.prayer-schedules.update', $psch->id) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Nama Jadwal Sholat</label>
                <input type="text" value="{{ $psch->name }}" readonly class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1 text-emerald-600">Jam Pembukaan (Default: 11:45)</label>
                    <input type="time" name="start_time" value="{{ $psch->start_time }}" required class="w-full px-3 py-2 rounded-xl border border-emerald-300 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-950/40 font-mono font-bold text-emerald-700 dark:text-emerald-300">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-rose-600">Jam Penutupan (Default: 12:30)</label>
                    <input type="time" name="end_time" value="{{ $psch->end_time }}" required class="w-full px-3 py-2 rounded-xl border border-rose-300 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 font-mono font-bold text-rose-700 dark:text-rose-300">
                </div>
            </div>

            <p class="text-[11px] text-slate-500 leading-relaxed bg-slate-50 dark:bg-slate-900/60 p-3 rounded-xl">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Tombol absensi sholat pada dashboard siswa secara otomatis nonaktif sebelum <strong>{{ $psch->start_time }}</strong> dan setelah <strong>{{ $psch->end_time }}</strong> WIB.
            </p>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold">Perbarui Jadwal Sholat</button>
        </form>
        @endforeach
    </div>

</div>
@endsection
