import React, { useState, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function AppLayout({ children, title, headerTitle, headerSubtitle, headerAction }) {
    const { auth, flash } = usePage().props;
    const user = auth?.user;

    const [isProfileOpen, setIsProfileOpen] = useState(false);
    const [isDarkMode, setIsDarkMode] = useState(() => {
        return localStorage.getItem('theme') === 'dark';
    });

    useEffect(() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            setIsDarkMode(true);
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            setIsDarkMode(false);
        }
    }, []);

    useEffect(() => {
        if (flash?.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flash.success,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }

        if (flash?.error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: flash.error,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }
    }, [flash]);

    const toggleTheme = () => {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            setIsDarkMode(false);
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            setIsDarkMode(true);
        }
    };

    const handleLogout = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    const formatTitleCase = (str) => {
        if (!str) return '';
        return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
    };

    return (
        <div className="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-200">
            {/* Top Navigation Bar */}
            <header className="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        
                        {/* Logo & Brand */}
                        <div className="flex items-center gap-3">
                            <Link href="/" className="flex items-center gap-3 group">
                                <img src="/images/logo.png" alt="Logo SMKN 6" className="h-10 sm:h-11 w-auto object-contain group-hover:scale-105 transition-transform duration-300" />
                                <div className="flex flex-col">
                                    <span className="font-heading font-semibold text-lg sm:text-xl tracking-tight text-slate-900 dark:text-white leading-none">
                                        SIX-PRESENCE
                                    </span>
                                    <span className="font-heading font-semibold text-[10px] sm:text-[11px] text-brand-600 dark:text-brand-400 tracking-wide mt-0.5">SMK NEGERI 6 JAKARTA</span>
                                </div>
                            </Link>
                        </div>

                        {/* Desktop Nav Links */}
                        <div className="hidden md:flex items-center gap-1">
                            {user && (
                                <>
                                    {user.is_admin && (
                                        <>
                                            <Link href="/admin/dashboard" className="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                                <i className="fa-solid fa-gauge text-brand-600"></i> Dashboard
                                            </Link>
                                            <Link href="/admin/students" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Siswa</Link>
                                            <Link href="/admin/classes" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Kelas</Link>
                                            <Link href="/admin/departments" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Jurusan</Link>
                                            <Link href="/admin/teachers" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Guru</Link>
                                            <Link href="/admin/reports" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Laporan</Link>
                                        </>
                                    )}
                                    {user.is_teacher && (
                                        <>
                                            <Link href="/guru/dashboard" className="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                                <i className="fa-solid fa-gauge text-brand-600"></i> Dashboard
                                            </Link>
                                            <Link href="/guru/attendance" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Daftar Absensi</Link>
                                            <Link href="/guru/prayer" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Absensi Sholat</Link>
                                        </>
                                    )}
                                    {user.is_student && (
                                        <>
                                            <Link href="/siswa/dashboard" className="px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2">
                                                <i className="fa-solid fa-house text-brand-600"></i> Dashboard
                                            </Link>
                                            <Link href="/siswa/history" className="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Riwayat Kehadiran</Link>
                                        </>
                                    )}
                                </>
                            )}
                        </div>

                        {/* Right Actions: Theme Toggle & Profile Dropdown */}
                        <div className="flex items-center gap-2.5">
                            <button onClick={toggleTheme} className="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors focus:outline-none" title="Toggle Dark/Light Mode">
                                <i className={`fa-solid ${isDarkMode ? 'fa-sun' : 'fa-moon'} text-sm`}></i>
                            </button>

                            {user ? (
                                <div className="relative">
                                    <button 
                                        type="button" 
                                        onClick={() => setIsProfileOpen(!isProfileOpen)} 
                                        className="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none"
                                    >
                                        <div className="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-brand-600 text-white font-heading font-bold flex items-center justify-center text-xs shadow-sm">
                                            {user.name.substring(0, 2).toUpperCase()}
                                        </div>
                                        <div className="hidden sm:flex flex-col text-left">
                                            <span className="text-sm font-semibold text-slate-800 dark:text-slate-200 leading-tight">
                                                {formatTitleCase(user.name)}
                                            </span>
                                            <span className="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                {user.is_student ? (user.student?.gender === 'P' ? 'Siswi' : 'Siswa') : 
                                                 user.is_teacher ? (user.teacher?.gender === 'P' ? 'Guru (Ibu)' : 'Guru (Bapak)') : 
                                                 'Admin System'}
                                            </span>
                                        </div>
                                        <i className="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                                    </button>

                                    {isProfileOpen && (
                                        <div className="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 py-2 transition-all z-50">
                                            <div className="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                                <p className="text-sm font-semibold text-slate-900 dark:text-white">{formatTitleCase(user.name)}</p>
                                                <p className="text-xs text-slate-500 truncate">{user.email}</p>
                                            </div>
                                            <Link href="/profile" onClick={() => setIsProfileOpen(false)} className="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                                <i className="fa-solid fa-user-gear text-slate-400 w-4"></i> Pengaturan Profil
                                            </Link>
                                            <form onSubmit={handleLogout}>
                                                <button type="submit" className="w-full flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-left">
                                                    <i className="fa-solid fa-right-from-bracket w-4"></i> Keluar / Logout
                                                </button>
                                            </form>
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <Link href="/login" className="px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-sm hover:bg-brand-700 shadow-md shadow-brand-500/20 transition-all">
                                    Login
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
                {headerTitle && (
                    <div className="mb-5 sm:mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
                        <div>
                            <h1 className="font-heading text-xl sm:text-2xl lg:text-3xl font-semibold text-slate-900 dark:text-white tracking-tight">
                                {headerTitle}
                            </h1>
                            {headerSubtitle && (
                                <p className="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
                                    {headerSubtitle}
                                </p>
                            )}
                        </div>
                        {headerAction && <div>{headerAction}</div>}
                    </div>
                )}

                {children}
            </main>

            {/* Mobile Bottom Navigation */}
            {user && (
                <div className="fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden px-4 py-2">
                    <div className="flex items-center justify-around">
                        {user.is_student && (
                            <>
                                <Link href="/siswa/dashboard" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-house text-lg"></i>
                                    <span>Dashboard</span>
                                </Link>
                                <Link href="/siswa/history" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-clock-rotate-left text-lg"></i>
                                    <span>Riwayat</span>
                                </Link>
                                <Link href="/profile" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-user text-lg"></i>
                                    <span>Profil</span>
                                </Link>
                            </>
                        )}
                        {user.is_teacher && (
                            <>
                                <Link href="/guru/dashboard" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-gauge text-lg"></i>
                                    <span>Dashboard</span>
                                </Link>
                                <Link href="/guru/attendance" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-list-check text-lg"></i>
                                    <span>Absensi</span>
                                </Link>
                                <Link href="/guru/prayer" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-mosque text-lg"></i>
                                    <span>Sholat</span>
                                </Link>
                                <Link href="/profile" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-user text-lg"></i>
                                    <span>Profil</span>
                                </Link>
                            </>
                        )}
                        {user.is_admin && (
                            <>
                                <Link href="/admin/dashboard" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-gauge text-lg"></i>
                                    <span>Dashboard</span>
                                </Link>
                                <Link href="/admin/students" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-user-graduate text-lg"></i>
                                    <span>Siswa</span>
                                </Link>
                                <Link href="/admin/reports" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-file-invoice text-lg"></i>
                                    <span>Laporan</span>
                                </Link>
                                <Link href="/profile" className="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-brand-600 text-xs font-semibold py-1">
                                    <i className="fa-solid fa-user-gear text-lg"></i>
                                    <span>Profil</span>
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            )}

            {/* Footer */}
            <footer className="hidden sm:block bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 transition-colors">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex sm:items-center sm:justify-between">
                    <div className="flex items-center justify-center sm:justify-start gap-2.5">
                        <img src="/images/logo.png" alt="Logo SMKN 6" className="h-6 w-auto object-contain" />
                        <p className="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                            &copy; {new Date().getFullYear()} <span className="font-heading font-semibold text-slate-800 dark:text-slate-200">SMK NEGERI 6 JAKARTA</span> — Sistem Absensi Digital Sekolah.
                        </p>
                    </div>
                    <div className="mt-3 sm:mt-0 flex items-center justify-center gap-4 text-xs text-slate-400 font-medium">
                        <span><i className="fa-solid fa-shield-halved text-brand-600"></i> Terverifikasi Geolocation & Live Selfie</span>
                    </div>
                </div>
            </footer>
        </div>
    );
}
