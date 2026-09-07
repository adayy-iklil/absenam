import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';

export default function TeacherPrayerList({ prayerAttendances, teacher, filters }) {
    const [selectedDate, setSelectedDate] = useState(filters?.date || new Date().toISOString().split('T')[0]);
    const [selectedStatus, setSelectedStatus] = useState(filters?.status || '');

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/guru/prayer', {
            date: selectedDate,
            status: selectedStatus,
        });
    };

    const handleConfirm = (id, status) => {
        Swal.fire({
            title: 'Konfirmasi Absensi Sholat',
            text: `Ubah status konfirmasi sholat menjadi ${status}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal',
        }).then((res) => {
            if (res.isConfirmed) {
                router.post(`/guru/prayer/${id}/confirm`, { status });
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Absensi Sholat Dzuhur Siswa"
            headerSubtitle="Monitoring dan konfirmasi absensi sholat siswa di masjid sekolah"
        >
            <Head title="Absensi Sholat Dzuhur" />

            <div className="space-y-6">
                {/* Filter Form */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <form onSubmit={handleFilter} className="flex flex-wrap items-center gap-4">
                        <div className="flex-1 min-w-[150px]">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal:</label>
                            <input
                                type="date"
                                value={selectedDate}
                                onChange={(e) => setSelectedDate(e.target.value)}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div className="flex-1 min-w-[150px]">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Status:</label>
                            <select
                                value={selectedStatus}
                                onChange={(e) => setSelectedStatus(e.target.value)}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div className="pt-5">
                            <button
                                type="submit"
                                className="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md"
                            >
                                Filter Data
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
                                    <th className="px-4 py-3">Waktu</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">Lokasi</th>
                                    <th className="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {prayerAttendances?.data && prayerAttendances.data.length > 0 ? (
                                    prayerAttendances.data.map((pAtt) => (
                                        <tr key={pAtt.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                                {pAtt.student?.name || pAtt.student?.user?.name}
                                                <span className="block text-[10px] text-slate-400 font-normal">NIS: {pAtt.student?.nis}</span>
                                            </td>
                                            <td className="px-4 py-3">{pAtt.student?.class_model?.name || '-'}</td>
                                            <td className="px-4 py-3 font-mono">{pAtt.time} WIB</td>
                                            <td className="px-4 py-3">
                                                <span className={`px-2.5 py-1 rounded-full font-bold text-[10px] ${
                                                    pAtt.status === 'Hadir' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300'
                                                }`}>
                                                    {pAtt.status}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 truncate max-w-[200px]">{pAtt.address}</td>
                                            <td className="px-4 py-3 text-right flex items-center justify-end gap-1.5">
                                                <button
                                                    onClick={() => handleConfirm(pAtt.id, 'Hadir')}
                                                    className="px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-semibold text-[10px]"
                                                >
                                                    Setujui
                                                </button>
                                                <button
                                                    onClick={() => handleConfirm(pAtt.id, 'Ditolak')}
                                                    className="px-2.5 py-1 rounded-lg bg-rose-600 text-white font-semibold text-[10px]"
                                                >
                                                    Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Tidak ada data absensi sholat yang ditemukan.
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
