import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminDepartmentsIndex({ departments }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editDept, setEditDept] = useState(null);
    const [form, setForm] = useState({ code: '', name: '' });

    const openAddModal = () => {
        setEditDept(null);
        setForm({ code: '', name: '' });
        setIsModalOpen(true);
    };

    const openEditModal = (d) => {
        setEditDept(d);
        setForm({ code: d.code, name: d.name });
        setIsModalOpen(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editDept) {
            router.post(`/admin/departments/${editDept.id}/update`, form, {
                onSuccess: () => setIsModalOpen(false),
            });
        } else {
            router.post('/admin/departments/store', form, {
                onSuccess: () => setIsModalOpen(false),
            });
        }
    };

    return (
        <AppLayout
            headerTitle="Manajemen Jurusan / Kompetensi Keahlian"
            headerSubtitle="Daftar 7 Jurusan Resmi di SMK Negeri 6 Jakarta"
            headerAction={
                <button
                    onClick={openAddModal}
                    className="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md transition-all flex items-center gap-2"
                >
                    + Tambah Jurusan Baru
                </button>
            }
        >
            <Head title="Manajemen Jurusan" />

            <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th className="px-4 py-3">Kode Jurusan</th>
                                <th className="px-4 py-3">Nama Kompetensi Keahlian</th>
                                <th className="px-4 py-3">Jumlah Siswa</th>
                                <th className="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                            {departments && departments.length > 0 ? (
                                departments.map((d) => (
                                    <tr key={d.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td className="px-4 py-3 font-mono font-bold text-brand-600">{d.code}</td>
                                        <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{d.name}</td>
                                        <td className="px-4 py-3 font-bold">{d.students_count || 0} Siswa</td>
                                        <td className="px-4 py-3 text-right">
                                            <button
                                                onClick={() => openEditModal(d)}
                                                className="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-semibold text-[10px]"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="4" className="px-4 py-6 text-center text-slate-400 font-medium">
                                        Belum ada data jurusan.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 relative">
                        <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                            {editDept ? 'Edit Jurusan' : 'Tambah Jurusan Baru'}
                        </h3>

                        <form onSubmit={handleSubmit} className="space-y-3 text-xs">
                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kode Jurusan (misal: MP, RPL, DKV):</label>
                                <input
                                    type="text"
                                    value={form.code}
                                    onChange={(e) => setForm({ ...form, code: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Jurusan:</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div className="flex items-center justify-end gap-3 pt-3">
                                <button
                                    type="button"
                                    onClick={() => setIsModalOpen(false)}
                                    className="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    className="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold"
                                >
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
