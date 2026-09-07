import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function ProfileShow({ user }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/profile/password', {
            onSuccess: () => reset(),
        });
    };

    return (
        <AppLayout
            headerTitle="Pengaturan Profil Akun"
            headerSubtitle="Informasi data diri dan ubah kata sandi akun"
        >
            <Head title="Pengaturan Profil" />

            <div className="max-w-3xl space-y-6">
                {/* User Info Card */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Informasi Akun
                    </h3>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div className="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <span className="text-slate-500 block">Nama Lengkap:</span>
                            <span className="font-bold text-slate-900 dark:text-white text-sm">{user?.name}</span>
                        </div>

                        <div className="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <span className="text-slate-500 block">Email / Username:</span>
                            <span className="font-bold text-slate-900 dark:text-white text-sm">{user?.email}</span>
                        </div>

                        <div className="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <span className="text-slate-500 block">Role Akun:</span>
                            <span className="font-bold text-brand-600 dark:text-brand-400 text-sm">
                                {user?.is_student ? 'Siswa' : user?.is_teacher ? 'Guru' : 'Administrator'}
                            </span>
                        </div>

                        {user?.student && (
                            <div className="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                                <span className="text-slate-500 block">NIS & Kelas:</span>
                                <span className="font-bold text-slate-900 dark:text-white text-sm">
                                    {user.student.nis} • {user.student.class_name}
                                </span>
                            </div>
                        )}

                        {user?.teacher && (
                            <div className="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                                <span className="text-slate-500 block">NIP Guru:</span>
                                <span className="font-bold text-slate-900 dark:text-white text-sm">
                                    {user.teacher.nip}
                                </span>
                            </div>
                        )}
                    </div>
                </div>

                {/* Password Form */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Ubah Password Akun
                    </h3>

                    <form onSubmit={handleSubmit} className="space-y-4 text-xs">
                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Password Lama Saat Ini:
                            </label>
                            <input
                                type="password"
                                value={data.current_password}
                                onChange={(e) => setData('current_password', e.target.value)}
                                required
                                placeholder="Masukkan password lama..."
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                            {errors.current_password && <p className="text-red-500 text-xs mt-1">{errors.current_password}</p>}
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Password Baru (Min 6 karakter):
                            </label>
                            <input
                                type="password"
                                value={data.new_password}
                                onChange={(e) => setData('new_password', e.target.value)}
                                required
                                placeholder="Masukkan password baru..."
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                            {errors.new_password && <p className="text-red-500 text-xs mt-1">{errors.new_password}</p>}
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Konfirmasi Password Baru:
                            </label>
                            <input
                                type="password"
                                value={data.new_password_confirmation}
                                onChange={(e) => setData('new_password_confirmation', e.target.value)}
                                required
                                placeholder="Ketik ulang password baru..."
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div className="pt-2">
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md transition-all"
                            >
                                {processing ? 'Menyimpan...' : 'Simpan Password Baru'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
