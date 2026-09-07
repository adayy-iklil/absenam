import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';

export default function AdminTeachersIndex({ teachers, departments, classes }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editTeacher, setEditTeacher] = useState(null);

    const [form, setForm] = useState({
        name: '',
        email: '',
        nip: '',
        password: '',
        department_id: '',
        teacher_class_id: '',
        gender: 'L',
        phone: '',
    });

    const openAddModal = () => {
        setEditTeacher(null);
        setForm({
            name: '',
            email: '',
            nip: '',
            password: 'Rahasia6#',
            department_id: '',
            teacher_class_id: '',
            gender: 'L',
            phone: '08123456789',
        });
        setIsModalOpen(true);
    };

    const openEditModal = (t) => {
        setEditTeacher(t);
        setForm({
            name: t.name,
            email: t.user?.email || '',
            nip: t.nip,
            password: '',
            department_id: t.department_id || '',
            teacher_class_id: t.teacher_class_id || '',
            gender: t.gender || 'L',
            phone: t.phone || '',
        });
        setIsModalOpen(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editTeacher) {
            router.post(`/admin/teachers/${editTeacher.id}/update`, form, {
                onSuccess: () => setIsModalOpen(false),
            });
        } else {
            router.post('/admin/teachers/store', form, {
                onSuccess: () => setIsModalOpen(false),
            });
        }
    };

    const handleDelete = (id, name) => {
        Swal.fire({
            title: 'Hapus Data Guru',
            text: `Apakah Anda yakin ingin menghapus data guru ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
        }).then((res) => {
            if (res.isConfirmed) {
                router.post(`/admin/teachers/${id}/delete`);
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Manajemen Data Guru & Wali Kelas"
            headerSubtitle="Kelola data tenaga pengajar dan penugasan Wali Kelas binaan"
            headerAction={
                <button
                    onClick={openAddModal}
                    className="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md transition-all flex items-center gap-2"
                >
                    + Tambah Guru Baru
                </button>
            }
        >
            <Head title="Manajemen Guru" />

            <div className="space-y-6">
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th className="px-4 py-3">NIP</th>
                                    <th className="px-4 py-3">Nama Guru</th>
                                    <th className="px-4 py-3">Email</th>
                                    <th className="px-4 py-3">Penugasan Wali Kelas</th>
                                    <th className="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {teachers?.data && teachers.data.length > 0 ? (
                                    teachers.data.map((t) => (
                                        <tr key={t.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-mono font-bold text-slate-900 dark:text-white">{t.nip}</td>
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{t.name}</td>
                                            <td className="px-4 py-3 text-slate-500">{t.user?.email || '-'}</td>
                                            <td className="px-4 py-3">
                                                {t.teacher_class_model ? (
                                                    <span className="px-2.5 py-1 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-300 font-bold text-[10px]">
                                                        Wali Kelas {t.teacher_class_model.name}
                                                    </span>
                                                ) : (
                                                    <span className="text-slate-400 font-medium">Guru Pengajar</span>
                                                )}
                                            </td>
                                            <td className="px-4 py-3 text-right flex items-center justify-end gap-2">
                                                <button
                                                    onClick={() => openEditModal(t)}
                                                    className="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-semibold text-[10px]"
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(t.id, t.name)}
                                                    className="px-2.5 py-1 rounded-lg bg-rose-600 text-white font-semibold text-[10px]"
                                                >
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="5" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Belum ada data guru.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 relative">
                        <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                            {editTeacher ? 'Edit Data Guru' : 'Tambah Guru Baru'}
                        </h3>

                        <form onSubmit={handleSubmit} className="space-y-3 text-xs">
                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Guru (dengan gelar):</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">NIP:</label>
                                <input
                                    type="text"
                                    value={form.nip}
                                    onChange={(e) => setForm({ ...form, nip: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Login:</label>
                                <input
                                    type="email"
                                    value={form.email}
                                    onChange={(e) => setForm({ ...form, email: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Password {editTeacher ? '(Kosongkan jika tidak diubah)' : ''}:
                                </label>
                                <input
                                    type="password"
                                    value={form.password}
                                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                                    required={!editTeacher}
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Penugasan Wali Kelas:</label>
                                <select
                                    value={form.teacher_class_id}
                                    onChange={(e) => setForm({ ...form, teacher_class_id: e.target.value })}
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                >
                                    <option value="">Bukan Wali Kelas (Guru Pengajar)</option>
                                    {classes?.map((c) => (
                                        <option key={c.id} value={c.id}>Wali Kelas {c.name}</option>
                                    ))}
                                </select>
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
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
