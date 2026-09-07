import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminAnnouncementsIndex({ announcements }) {
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [targetRole, setTargetRole] = useState('all');

    const handleSubmit = (e) => {
        e.preventDefault();
        router.post('/admin/announcements/store', {
            title,
            content,
            target_role: targetRole,
        }, {
            onSuccess: () => {
                setTitle('');
                setContent('');
                setTargetRole('all');
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Pengumuman Resmi Sekolah"
            headerSubtitle="Terbitkan informasi dan pengumuman untuk siswa dan guru SMKN 6 Jakarta"
        >
            <Head title="Pengumuman Sekolah" />

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Form Card */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm md:col-span-1">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Buat Pengumuman Baru
                    </h3>

                    <form onSubmit={handleSubmit} className="space-y-4 text-xs">
                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Judul Pengumuman:</label>
                            <input
                                type="text"
                                value={title}
                                onChange={(e) => setTitle(e.target.value)}
                                required
                                placeholder="Masukkan judul..."
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Target Sasaran:</label>
                            <select
                                value={targetRole}
                                onChange={(e) => setTargetRole(e.target.value)}
                                required
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            >
                                <option value="all">Semua (Siswa & Guru)</option>
                                <option value="siswa">Khusus Siswa</option>
                                <option value="guru">Khusus Guru</option>
                            </select>
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Isi Pesan Pengumuman:</label>
                            <textarea
                                value={content}
                                onChange={(e) => setContent(e.target.value)}
                                required
                                rows="5"
                                placeholder="Tuliskan isi pengumuman lengkap..."
                                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            className="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold shadow-md"
                        >
                            Terbitkan Pengumuman
                        </button>
                    </form>
                </div>

                {/* List Board */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm md:col-span-2">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Daftar Pengumuman Diterbitkan
                    </h3>

                    <div className="space-y-4">
                        {announcements && announcements.length > 0 ? (
                            announcements.map((ann) => (
                                <div key={ann.id} className="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                                    <div className="flex items-center justify-between mb-1">
                                        <h4 className="font-bold text-sm text-slate-900 dark:text-white">{ann.title}</h4>
                                        <span className="text-[10px] px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-300 font-bold uppercase">
                                            {ann.target_role}
                                        </span>
                                    </div>
                                    <p className="text-xs text-slate-600 dark:text-slate-300 mt-1 whitespace-pre-line">{ann.content}</p>
                                </div>
                            ))
                        ) : (
                            <p className="text-xs text-slate-400 text-center py-6">Belum ada pengumuman yang diterbitkan.</p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
