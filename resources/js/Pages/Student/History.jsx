import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function StudentHistory({ student, attendances, prayerAttendances, month, year }) {
    const [selectedMonth, setSelectedMonth] = useState(month);
    const [selectedYear, setSelectedYear] = useState(year);

    const months = [
        { id: 1, name: 'Januari' },
        { id: 2, name: 'Februari' },
        { id: 3, name: 'Maret' },
        { id: 4, name: 'April' },
        { id: 5, name: 'Mei' },
        { id: 6, name: 'Juni' },
        { id: 7, name: 'Juli' },
        { id: 8, name: 'Agustus' },
        { id: 9, name: 'September' },
        { id: 10, name: 'Oktober' },
        { id: 11, name: 'November' },
        { id: 12, name: 'Desember' },
    ];

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/siswa/history', { month: selectedMonth, year: selectedYear });
    };

    return (
        <AppLayout
            headerTitle="Riwayat Kehadiran Siswa"
            headerSubtitle={`Data rekapitulasi presensi harian & sholat bulan ${months.find(m => m.id === selectedMonth)?.name} ${selectedYear}`}
        >
            <Head title="Riwayat Kehadiran" />

            <div className="space-y-6">
                {/* Filter Month & Year Form */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <form onSubmit={handleFilter} className="flex flex-wrap items-center gap-4">
                        <div className="flex-1 min-w-[150px]">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Bulan:</label>
                            <select
                                value={selectedMonth}
                                onChange={(e) => setSelectedMonth(Number(e.target.value))}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            >
                                {months.map((m) => (
                                    <option key={m.id} value={m.id}>{m.name}</option>
                                ))}
                            </select>
                        </div>

                        <div className="flex-1 min-w-[120px]">
                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tahun:</label>
                            <select
                                value={selectedYear}
                                onChange={(e) => setSelectedYear(Number(e.target.value))}
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-white"
                            >
                                {[2024, 2025, 2026, 2027].map((y) => (
                                    <option key={y} value={y}>{y}</option>
                                ))}
                            </select>
                        </div>

                        <div className="pt-5">
                            <button
                                type="submit"
                                className="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition-all shadow-md"
                            >
                                Filter Data
                            </button>
                        </div>
                    </form>
                </div>

                {/* Table Presensi Harian */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Riwayat Presensi Harian
                    </h3>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th className="px-4 py-3">Tanggal</th>
                                    <th className="px-4 py-3">Waktu</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">Alamat GPS</th>
                                    <th className="px-4 py-3">Catatan</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {attendances && attendances.length > 0 ? (
                                    attendances.map((att) => (
                                        <tr key={att.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{att.date}</td>
                                            <td className="px-4 py-3 font-mono">{att.time} WIB</td>
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
                                            <td className="px-4 py-3 italic text-slate-400">{att.teacher_notes || '-'}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="5" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Belum ada data presensi harian pada bulan ini.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Table Presensi Sholat */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Riwayat Presensi Sholat Dzuhur
                    </h3>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead className="bg-slate-50 dark:bg-slate-800/60 uppercase font-semibold text-slate-500 text-[11px] border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th className="px-4 py-3">Tanggal</th>
                                    <th className="px-4 py-3">Waktu</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">Lokasi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {prayerAttendances && prayerAttendances.length > 0 ? (
                                    prayerAttendances.map((pAtt) => (
                                        <tr key={pAtt.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                            <td className="px-4 py-3 font-semibold text-slate-900 dark:text-white">{pAtt.date}</td>
                                            <td className="px-4 py-3 font-mono">{pAtt.time} WIB</td>
                                            <td className="px-4 py-3">
                                                <span className="px-2.5 py-1 rounded-full font-bold text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                                    {pAtt.status}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 truncate max-w-[200px]">{pAtt.address}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="4" className="px-4 py-6 text-center text-slate-400 font-medium">
                                            Belum ada data presensi sholat pada bulan ini.
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
