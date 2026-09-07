import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';

export default function TeacherAttendanceList({ attendances, classes, teacher, filters }) {
    const [search, setSearch] = useState(filters?.search || '');
    const [selectedClass, setSelectedClass] = useState(filters?.class_id || '');
    const [selectedStatus, setSelectedStatus] = useState(filters?.status || '');
    const [selectedDate, setSelectedDate] = useState(filters?.date || new Date().toISOString().split('T')[0]);

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/guru/attendance', {
            search,
            class_id: selectedClass,
            status: selectedStatus,
            date: selectedDate,
        });
    };

    const handleConfirm = (id, status) => {
        Swal.fire({
            title: 'Konfirmasi Absensi',
            text: `Ubah status absensi menjadi ${status}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal',
        }).then((res) => {
            if (res.isConfirmed) {
                router.post(`/guru/attendance/${id}/confirm`, { status });
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Daftar Absensi Harian Siswa"
            headerSubtitle="Kelola dan konfirmasi status presensi siswa"
        >
            <Head title="Daftar Absensi Harian" />

            <div className="space-y-6">
                {/* Filter Form */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <form onSubmit={handleFilter} className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Cari Nama / NIS:</label>
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Masukkan nama atau NIS..."
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelas:</label>
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

                        <div>
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status:</label>
                            <select
                                value={selectedStatus}
                                onChange={(e) => setSelectedStatus(e.target.value)}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                            </select>
                        </div>

                        <div className="flex items-end gap-2">
                            <input
                                type="date"
                                value={selectedDate}
                                onChange={(e) => setSelectedDate(e.target.value)}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            />
                            <button
                                type="submit"
                                className="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md"
                            >
                                Cari
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
                                    <th className="px-4 py-3">Siswa</th>
                                    <th className="px-4 py-3">Kelas</th>
                                    <th className="px-4 py-3">Tanggal / Jam</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">Alamat</th>
                                    <th className="px-4 py-3 text-right">Aksi Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {attendances?.data && attendances.data.length > 0 ? (
                                    attendances.data.map((att) => (
                                        <tr key={att.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                                {att.student?.name || att.student?.user?.name}
                                                <span className="block text-[10px] text-slate-400 font-normal">NIS: {att.student?.nis}</span>
                                            </td>
                                            <td className="px-4 py-3">{att.student?.class_model?.name || '-'}</td>
                                            <td className="px-4 py-3">
                                                <span>{att.date}</span>
                                                <span className="block font-mono text-[10px] text-slate-400">{att.time} WIB</span>
                                            </td>
                                            <td className="px-4 py-3">
                                                <span className={`px-2.5 py-1 rounded-full font-bold text-[10px] ${
                                                    att.status === 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' :
                                                    att.status === 'Terlambat' ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-950 dark:text-cyan-300' :
                                                    att.status === 'Izin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300' :
                                                    att.status === 'Sakit' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' :
                                                    'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300'
                                                }`}>
                                                    {att.status}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 truncate max-w-[200px]">{att.address}</td>
                                            <td className="px-4 py-3 text-right flex items-center justify-end gap-1.5">
                                                <button
                                                    onClick={() => handleConfirm(att.id, 'Hadir')}
                                                    className="px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-semibold text-[10px]"
                                                >
                                                    Hadir
                                                </button>
                                                <button
                                                    onClick={() => handleConfirm(att.id, 'Terlambat')}
                                                    className="px-2.5 py-1 rounded-lg bg-cyan-600 text-white font-semibold text-[10px]"
                                                >
                                                    Terlambat
                                                </button>
                                                <button
                                                    onClick={() => handleConfirm(att.id, 'Izin')}
                                                    className="px-2.5 py-1 rounded-lg bg-blue-600 text-white font-semibold text-[10px]"
                                                >
                                                    Izin
                                                </button>
                                                <button
                                                    onClick={() => handleConfirm(att.id, 'Sakit')}
                                                    className="px-2.5 py-1 rounded-lg bg-amber-600 text-white font-semibold text-[10px]"
                                                >
                                                    Sakit
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Tidak ada data absensi yang ditemukan.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
