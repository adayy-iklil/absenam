import React, { useState, useEffect } from 'react';
import { useForm, usePage } from '@inertiajs/react';

export default function Login() {
    const { flash } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showSplash, setShowSplash] = useState(false);
    const [progress, setProgress] = useState(0);
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

    useEffect(() => {
        if (!sessionStorage.getItem('splashShown')) {
            setShowSplash(true);
            sessionStorage.setItem('splashShown', 'true');

            setTimeout(() => setProgress(100), 100);
            setTimeout(() => {
                setShowSplash(false);
            }, 1400);
        }
    }, []);

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/login');
    };

    const handleDemoLogin = (emailVal, passwordVal) => {
        setData({
            email: emailVal,
            password: passwordVal,
            remember: false,
        });

        setTimeout(() => {
            post('/login', {
                data: {
                    email: emailVal,
                    password: passwordVal,
                    remember: false,
                }
            });
        }, 100);
    };

    return (
        <div className="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-200 relative">
            
            {/* Dark/Light Mode Toggle Button on Top Right */}
            <div className="absolute top-4 right-4 z-40">
                <button
                    onClick={toggleTheme}
                    className="p-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 shadow-sm transition-colors focus:outline-none flex items-center gap-2 text-xs font-semibold"
                    title="Toggle Mode Terang / Gelap"
                >
                    <i className={`fa-solid ${isDarkMode ? 'fa-sun text-amber-400' : 'fa-moon text-indigo-600'} text-sm`}></i>
                    <span className="hidden sm:inline">{isDarkMode ? 'Mode Terang' : 'Mode Gelap'}</span>
                </button>
            </div>

            {/* Splash Screen */}
            {showSplash && (
                <div className="fixed inset-0 z-50 bg-slate-950 flex flex-col items-center justify-center transition-opacity duration-700">
                    <div className="relative flex flex-col items-center">
                        <div className="absolute -inset-6 rounded-full bg-brand-500/20 blur-2xl animate-pulse"></div>
                        <img src="/images/logo.png" alt="Logo SMKN 6" className="h-28 w-auto object-contain z-10 mb-6" />
                        <h2 className="font-heading text-2xl font-bold text-white tracking-tight z-10">SIX-PRESENCE</h2>
                        <p className="font-heading text-xs text-brand-400 font-semibold uppercase tracking-widest mt-1 z-10">SMK NEGERI 6 JAKARTA</p>
                        <div className="w-36 h-1 bg-slate-800 rounded-full mt-6 overflow-hidden z-10">
                            <div className="h-full bg-brand-500 rounded-full transition-all duration-1000" style={{ width: `${progress}%` }}></div>
                        </div>
                    </div>
                </div>
            )}

            <div className="max-w-md w-full my-6">
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-200/80 dark:border-slate-800 backdrop-blur-md">
                    
                    {/* Header */}
                    <div className="flex flex-col items-center text-center mb-6">
                        <div className="mb-4">
                            <img src="/images/logo.png" alt="Logo Resmi SMKN 6" className="h-24 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300" />
                        </div>
                        <h1 className="font-heading text-2xl sm:text-3xl font-semibold text-slate-900 dark:text-white tracking-tight">
                            SIX-PRESENCE
                        </h1>
                        <p className="font-heading text-xs font-semibold text-brand-600 dark:text-brand-400 mt-1 uppercase tracking-wider">
                            Sistem Absensi Digital SMK NEGERI 6 JAKARTA
                        </p>
                    </div>

                    {/* Flash Messages */}
                    {flash?.error && (
                        <div className="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium flex items-center gap-3">
                            <i className="fa-solid fa-circle-exclamation text-base flex-shrink-0"></i>
                            <div>{flash.error}</div>
                        </div>
                    )}

                    {flash?.success && (
                        <div className="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium flex items-center gap-3">
                            <i className="fa-solid fa-circle-check text-base flex-shrink-0"></i>
                            <div>{flash.success}</div>
                        </div>
                    )}

                    {/* Login Form */}
                    <form onSubmit={handleSubmit} className="space-y-5 text-sm">
                        <div>
                            <label htmlFor="email" className="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Email / NIS / NIP
                            </label>
                            <div className="relative">
                                <input
                                    type="text"
                                    id="email"
                                    name="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    required
                                    autoFocus
                                    placeholder="Masukkan NIS, NIP, atau Email Anda"
                                    className="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-normal text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all"
                                />
                                <i className="fa-solid fa-user absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                            </div>
                            {errors.email && <p className="text-red-500 text-xs mt-1 font-medium">{errors.email}</p>}
                        </div>

                        <div>
                            <label htmlFor="password" className="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Password
                            </label>
                            <div className="relative">
                                <input
                                    type={showPassword ? 'text' : 'password'}
                                    id="password"
                                    name="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    required
                                    placeholder="Masukkan Password Anda"
                                    className="w-full pl-10 pr-10 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-normal text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all"
                                />
                                <i className="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                                >
                                    <i className={`fa-solid ${showPassword ? 'fa-eye-slash' : 'fa-eye'} text-sm`}></i>
                                </button>
                            </div>
                            {errors.password && <p className="text-red-500 text-xs mt-1 font-medium">{errors.password}</p>}
                        </div>

                        <div className="flex items-center justify-between pt-1">
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                />
                                <span className="font-medium text-slate-600 dark:text-slate-400">Ingat Saya</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-lg shadow-brand-500/25 transition-all text-center"
                        >
                            {processing ? 'Memproses...' : 'Login'}
                        </button>
                    </form>

                    {/* Quick Demo Login Actions Section (Clean Text without Icons) */}
                    <div className="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <p className="text-xs font-semibold text-slate-500 dark:text-slate-400 text-center uppercase tracking-wider mb-3">
                            Akses Cepat Demo Login:
                        </p>
                        <div className="grid grid-cols-3 gap-2">
                            <button
                                type="button"
                                onClick={() => handleDemoLogin('admin@absenam.sch.id', 'Rahasia6#')}
                                className="py-2.5 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold text-center border border-slate-200 dark:border-slate-700 transition-all"
                            >
                                Super Admin
                            </button>

                            <button
                                type="button"
                                onClick={() => handleDemoLogin('budi.guru@absenam.sch.id', 'Rahasia6#')}
                                className="py-2.5 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold text-center border border-slate-200 dark:border-slate-700 transition-all"
                            >
                                Guru Demo
                            </button>

                            <button
                                type="button"
                                onClick={() => handleDemoLogin('20357', 'Rahasia6#')}
                                className="py-2.5 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold text-center border border-slate-200 dark:border-slate-700 transition-all"
                            >
                                Siswa Demo
                            </button>
                        </div>
                    </div>

                </div>

                <div className="mt-6 text-center text-xs text-slate-400 font-medium">
                    &copy; {new Date().getFullYear()} <span className="font-heading font-semibold text-slate-600 dark:text-slate-300">SMK NEGERI 6 JAKARTA</span> — SIX-PRESENCE System
                </div>
            </div>
        </div>
    );
}
