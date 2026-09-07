import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminReports({ attendances, classes, departments, filters }) {
    const [startDate, setStartDate] = useState(filters?.start_date || '');
    const [endDate, setEndDate] = useState(filters?.end_date || '');
    const [selectedClass, setSelectedClass] = useState(filters?.class_id || '');
    const [selectedDept, setSelectedDept] = useState(filters?.department_id || '');
    const [selectedStatus, setSelectedStatus] = useState(filters?.status || '');

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/admin/reports', {
            start_date: startDate,
            end_date: endDate,
            class_id: selectedClass,
            department_id: selectedDept,
            status: selectedStatus,
        });
    };

    const handleExportExcel = () => {
        const queryParams = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            class_id: selectedClass,
            department_id: selectedDept,
            status: selectedStatus,
            export: 'excel'
        }).toString();
        window.location.href = `/admin/reports?${queryParams}`;
    };

    const handlePrint = () => {
        const queryParams = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            class_id: selectedClass,
            department_id: selectedDept,
            status: selectedStatus,
            export: 'print'
        }).toString();
        window.open(`/admin/reports?${queryParams}`, '_blank');
    };

    return (
        <AppLayout
            headerTitle="Rekapitulasi & Laporan Absensi"
            headerSubtitle="Filter data kehadiran siswa, cetak dokumen resmi, dan unduh format Excel / CSV"
        >
            <Head title="Laporan Absensi" />

            <div className="space-y-6">
                {/* Filter & Action Buttons */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <form onSubmit={handleFilter} className="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai:</label>
                            <input
                                type="date"
                                value={startDate}
                                onChange={(e) => setStartDate(e.target.value)}
                                className="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai:</label>
                            <input
                                type="date"
                                value={endDate}
                                onChange={(e) => setEndDate(e.target.value)}
                                className="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelas:</label>
                            <select
                                value={selectedClass}
                                onChange={(e) => setSelectedClass(e.target.value)}
                                className="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Kelas</option>
                                {classes?.map((c) => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jurusan:</label>
                            <select
                                value={selectedDept}
                                onChange={(e) => setSelectedDept(e.target.value)}
                                className="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Jurusan</option>
                                {departments?.map((d) => (
                                    <option key={d.id} value={d.id}>{d.code}</option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status:</label>
                            <select
                                value={selectedStatus}
                                onChange={(e) => setSelectedStatus(e.target.value)}
                                className="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            >
                                <option value="">Semua Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                            </select>
                        </div>

                        <div className="sm:col-span-5 flex items-center justify-between pt-2">
                            <button
                                type="submit"
                                className="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-md"
                            >
                                Tampilkan Filter
                            </button>

                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={handleExportExcel}
                                    className="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md"
                                >
                                    Unduh Excel / CSV
                                </button>
                                <button
                                    type="button"
                                    onClick={handlePrint}
                                    className="px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white font-semibold text-xs shadow-md"
                                >
                                    Cetak PDF / Print
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {/* Table */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th className="px-4 py-3">Tanggal</th>
                                    <th className="px-4 py-3">NIS</th>
                                    <th className="px-4 py-3">Nama Siswa</th>
                                    <th className="px-4 py-3">Kelas</th>
                                    <th className="px-4 py-3">Jurusan</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">Alamat Jalan GPS</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {attendances && attendances.length > 0 ? (
                                    attendances.map((att) => (
                                        <tr key={att.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                                {att.date}
                                                <span className="block text-[10px] text-slate-400 font-mono">{att.time} WIB</span>
                                            </td>
                                            <td className="px-4 py-3 font-mono font-bold">{att.student?.nis || '-'}</td>
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                                {att.student?.name || att.student?.user?.name}
                                            </td>
                                            <td className="px-4 py-3">{att.student?.class_model?.name || '-'}</td>
                                            <td className="px-4 py-3">{att.student?.department?.code || '-'}</td>
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
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="7" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Tidak ada data laporan yang sesuai dengan kriteria filter.
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
