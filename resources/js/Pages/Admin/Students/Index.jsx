import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';

export default function AdminStudentsIndex({ students, classes, departments, filters }) {
    const [search, setSearch] = useState(filters?.search || '');
    const [selectedClass, setSelectedClass] = useState(filters?.class_id || '');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editStudent, setEditStudent] = useState(null);

    const [form, setForm] = useState({
        name: '',
        email: '',
        nis: '',
        password: '',
        class_id: '',
        department_id: '',
        gender: 'L',
        phone: '',
    });

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/admin/students', { search, class_id: selectedClass });
    };

    const openAddModal = () => {
        setEditStudent(null);
        setForm({
            name: '',
            email: '',
            nis: '',
            password: 'Rahasia6#',
            class_id: classes && classes.length > 0 ? classes[0].id : '',
            department_id: departments && departments.length > 0 ? departments[0].id : '',
            gender: 'L',
            phone: '08123456789',
        });
        setIsModalOpen(true);
    };

    const openEditModal = (std) => {
        setEditStudent(std);
        setForm({
            name: std.name,
            email: std.user?.email || '',
            nis: std.nis,
            password: '',
            class_id: std.class_id,
            department_id: std.department_id,
            gender: std.gender || 'L',
            phone: std.phone || '',
        });
        setIsModalOpen(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editStudent) {
            router.post(`/admin/students/${editStudent.id}/update`, form, {
                onSuccess: () => setIsModalOpen(false),
            });
        } else {
            router.post('/admin/students/store', form, {
                onSuccess: () => setIsModalOpen(false),
            });
        }
    };

    const handleDelete = (id, name) => {
        Swal.fire({
            title: 'Hapus Data Siswa',
            text: `Apakah Anda yakin ingin menghapus data siswa ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
        }).then((res) => {
            if (res.isConfirmed) {
                router.post(`/admin/students/${id}/delete`);
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Manajemen Data Siswa Real SMKN 6 Jakarta"
            headerSubtitle="Kelola data siswa, tingkat kelas X-XII, dan akun login"
            headerAction={
                <button
                    onClick={openAddModal}
                    className="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md transition-all flex items-center gap-2"
                >
                    + Tambah Siswa Baru
                </button>
            }
        >
            <Head title="Manajemen Siswa" />

            <div className="space-y-6">
                {/* Search & Filter */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <form onSubmit={handleFilter} className="flex flex-wrap items-center gap-4">
                        <div className="flex-1 min-w-[200px]">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Cari Nama / NIS:</label>
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Ketik NIS atau nama siswa..."
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div className="w-48">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Filter Kelas:</label>
                            <select
                                value={selectedClass}
                                onChange={(e) => setSelectedClass(e.target.value)}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Kelas</option>
                                {classes?.map((c) => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                        </div>

                        <div className="pt-5">
                            <button
                                type="submit"
                                className="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md"
                            >
                                Cari Data
                            </button>
                        </div>
                    </form>
                </div>

                {/* Table */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th className="px-4 py-3">NIS</th>
                                    <th className="px-4 py-3">Nama Lengkap</th>
                                    <th className="px-4 py-3">Email Login</th>
                                    <th className="px-4 py-3">Kelas</th>
                                    <th className="px-4 py-3">Jurusan</th>
                                    <th className="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {students?.data && students.data.length > 0 ? (
                                    students.data.map((s) => (
                                        <tr key={s.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-mono font-bold text-slate-900 dark:text-white">{s.nis}</td>
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{s.name}</td>
                                            <td className="px-4 py-3 text-slate-500">{s.user?.email || `${s.nis}@absenam.sch.id`}</td>
                                            <td className="px-4 py-3">{s.class_model?.name || s.class_name || '-'}</td>
                                            <td className="px-4 py-3">{s.department?.name || '-'}</td>
                                            <td className="px-4 py-3 text-right flex items-center justify-end gap-2">
                                                <button
                                                    onClick={() => openEditModal(s)}
                                                    className="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-semibold text-[10px]"
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(s.id, s.name)}
                                                    className="px-2.5 py-1 rounded-lg bg-rose-600 text-white font-semibold text-[10px]"
                                                >
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Tidak ada data siswa ditemukan.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Modal Add/Edit */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 relative">
                        <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                            {editStudent ? 'Edit Data Siswa' : 'Tambah Siswa Baru'}
                        </h3>

                        <form onSubmit={handleSubmit} className="space-y-3 text-xs">
                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap:</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    required
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">NIS:</label>
                                <input
                                    type="text"
                                    value={form.nis}
                                    onChange={(e) => setForm({ ...form, nis: e.target.value })}
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
                                    Password {editStudent ? '(Kosongkan jika tidak diubah)' : ''}:
                                </label>
                                <input
                                    type="password"
                                    value={form.password}
                                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                                    required={!editStudent}
                                    className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelas:</label>
                                    <select
                                        value={form.class_id}
                                        onChange={(e) => setForm({ ...form, class_id: e.target.value })}
                                        required
                                        className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                                    >
                                        {classes?.map((c) => (
                                            <option key={c.id} value={c.id}>{c.name}</option>
                                        ))}
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
                                            <option key={d.id} value={d.id}>{d.name}</option>
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
