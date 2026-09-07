@extends('layouts.app')

@section('title', 'Pengumuman Sekolah - Absenam SMKN 6 Jakarta')

@section('header_title', 'Kelola Pengumuman Sekolah')
@section('header_subtitle', 'Terbitkan informasi atau instruksi penting kepada siswa dan guru SMKN 6 Jakarta.')

@section('header_action')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs hover:bg-slate-300 flex items-center gap-2 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <button onclick="document.getElementById('addAnnModal').classList.remove('hidden')" class="px-4 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-lg shadow-brand-500/25 flex items-center gap-2">
        <i class="fa-solid fa-bullhorn"></i> Buat Pengumuman Baru
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <div class="space-y-4">
            @forelse($announcements as $ann)
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300">
                            Target: {{ $ann->target_role }}
                        </span>
                        <h4 class="font-extrabold text-base text-slate-900 dark:text-white">{{ $ann->title }}</h4>
                    </div>
                    <span class="text-xs text-slate-400 font-mono">{{ $ann->created_at->format('d M Y H:i') }}</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $ann->content }}</p>
                <div class="mt-3 text-[11px] text-slate-400">Diterbitkan oleh: <strong>{{ $ann->author->name ?? 'Admin' }}</strong></div>
            </div>
            @empty
            <p class="text-xs text-slate-400 text-center py-6">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Modal Add Announcement -->
<div id="addAnnModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Terbitkan Pengumuman Baru</h3>
            <button onclick="document.getElementById('addAnnModal').classList.add('hidden')" class="text-slate-400 text-xl">&times;</button>
        </div>

        <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1">Judul Pengumuman</label>
                <input type="text" name="title" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
            </div>

            <div>
                <label class="block font-bold mb-1">Target Penerima</label>
                <select name="target_role" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <option value="all">Semua Pengguna (Siswa & Guru)</option>
                    <option value="siswa">Siswa Sahaja</option>
                    <option value="guru">Guru Sahaja</option>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">Isi Pengumuman</label>
                <textarea name="content" rows="4" required class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold">Terbitkan Pengumuman</button>
            </div>
        </form>
    </div>
</div>
@endsection
