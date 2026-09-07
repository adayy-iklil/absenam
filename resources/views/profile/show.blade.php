@extends('layouts.app')

@section('title', 'Pengaturan Profil - SIX-PRESENCE')

@section('header_title', 'Pengaturan Profil & Keamanan')
@section('header_subtitle', 'Kelola informasi akun dan perbarui password Anda.')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- User Information Card -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center gap-6">
        <div class="w-24 h-24 rounded-2xl bg-brand-100 dark:bg-brand-950 text-brand-600 flex items-center justify-center font-bold text-3xl ring-4 ring-brand-500/20">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="space-y-1 text-center sm:text-left">
            <span class="px-3 py-1 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 font-extrabold text-xs uppercase tracking-wider">
                {{ $user->role->display_name ?? $user->role->name }}
            </span>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h3>
            <p class="text-sm text-slate-500"><i class="fa-solid fa-envelope"></i> {{ $user->email }}</p>
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="glass-card rounded-3xl p-6 border border-slate-200 dark:border-slate-800">
        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-key text-brand-600"></i> Ganti Password Akun
        </h3>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Password Baru (Min. 6 Karakter)</label>
                <input type="password" name="new_password" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 font-mono">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-lg shadow-brand-500/25">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
