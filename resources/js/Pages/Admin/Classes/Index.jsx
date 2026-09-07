import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminClassesIndex({ classes, departments }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editClass, setEditClass] = useState(null);
    const [form, setForm] = useState({ name: '', department_id: '', grade: 'X' });

    const openAddModal = () => {
        setEditClass(null);
        setForm({
            name: '',
            department_id: departments && departments.length > 0 ? departments[0].id : '',
            grade: 'X',
        });
        setIsModalOpen(true);
    };

    const openEditModal = (c) => {
        setEditClass(c);
        setForm({ name: c.name, department_id: c.department_id, grade: c.grade });
        setIsModalOpen(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editClass) {
            router.post(`/admin/classes/${editClass.id}/update`, form, {
                onSuccess: () => setIsModalOpen(false),
            });
        } else {
            router.post('/admin/classes/store', form, {
                onSuccess: () => setIsModalOpen(false),
            });
        }
    };

    return (
        <AppLayout
            headerTitle="Manajemen Data Kelas SMKN 6 Jakarta"
            headerSubtitle="Daftar kelas aktif tingkat X, XI, XII seluruh kompetensi keahlian"
            headerAction={
                <button
                    onClick={openAddModal}
                    className="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md transition-all flex items-center gap-2"
                >
                    + Tambah Kelas Baru
                </button>
            }
        >
            <Head title="Manajemen Kelas" />

            <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th className="px-4 py-3">Nama Kelas</th>
                                <th className="px-4 py-3">Tingkat</th>
                                <th className="px-4 py-3">Jurusan</th>
                                <th className="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                            {classes && classes.length > 0 ? (
                                classes.map((c) => (
                                    <tr key={c.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{c.name}</td>
                                        <td className="px-4 py-3 font-bold text-brand-600">Tingkat {c.grade}</td>
                                        <td className="px-4 py-3">{c.department?.name || '-'}</td>
                                        <td className="px-4 py-3 text-right">
                                            <button
                                                onClick={() => openEditModal(c)}
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
                                        Belum ada data kelas.
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
                            {editClass ? 'Edit Kelas' : 'Tambah Kelas Baru'}
                        </h3>

                        <form onSubmit={handleSubmit} className="space-y-3 text-xs">
                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Kelas (misal: XI MP, XI RPL):</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Grade:</label>
                                    <select
                                        value={form.grade}
                                        onChange={(e) => setForm({ ...form, grade: e.target.value })}
                                        required
                                        className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                    >
                                        <option value="X">Tingkat X</option>
                                        <option value="XI">Tingkat XI</option>
                                        <option value="XII">Tingkat XII</option>
                                    </select>
                                </div>

                                <div>
                                    <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jurusan:</label>
                                    <select
                                        value={form.department_id}
                                        onChange={(e) => setForm({ ...form, department_id: e.target.value })}
                                        required
                                        className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                    >
                                        {departments?.map((d) => (
                                            <option key={d.id} value={d.id}>{d.code} - {d.name}</option>
                                        ))}
                                    </select>
                                </div>
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
