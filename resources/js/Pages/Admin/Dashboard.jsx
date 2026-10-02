import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminDashboard({
    totalStudents,
    totalTeachers,
    countHadir,
    countTerlambat,
    countIzin,
    countSakit,
    countAlpha,
    deptLabels,
    deptCounts,
    months,
    monthlyHadir,
}) {
    return (
        <AppLayout
            headerTitle="Dashboard Administrator System"
            headerSubtitle="Ringkasan statistik & data sistem absensi SMKN 6 Jakarta"
        >
            <Head title="Dashboard Admin" />

            <div className="space-y-6">
                {/* Stats Grid */}
                <div className="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4">
                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-slate-500 font-medium uppercase">Total Siswa</p>
                        <p className="font-heading text-2xl font-bold text-slate-900 dark:text-white mt-1">{totalStudents}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-slate-500 font-medium uppercase">Total Guru</p>
                        <p className="font-heading text-2xl font-bold text-slate-900 dark:text-white mt-1">{totalTeachers}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-emerald-600 font-medium uppercase">Hadir</p>
                        <p className="font-heading text-2xl font-bold text-emerald-600 mt-1">{countHadir}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-cyan-600 font-medium uppercase">Terlambat</p>
                        <p className="font-heading text-2xl font-bold text-cyan-600 mt-1">{countTerlambat}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-blue-600 font-medium uppercase">Izin</p>
                        <p className="font-heading text-2xl font-bold text-blue-600 mt-1">{countIzin}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-amber-600 font-medium uppercase">Sakit</p>
                        <p className="font-heading text-2xl font-bold text-amber-600 mt-1">{countSakit}</p>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p className="text-xs text-rose-600 font-medium uppercase">Alpha</p>
                        <p className="font-heading text-2xl font-bold text-rose-600 mt-1">{countAlpha}</p>
                    </div>
                </div>

                {/* Navigation Shortcuts */}
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <Link href="/admin/students" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Data Siswa</h4>
                        <p className="text-xs text-slate-400 mt-0.5">Kelola Siswa</p>
                    </Link>

                    <Link href="/admin/teachers" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Data Guru</h4>
                        <p className="text-xs text-slate-400 mt-0.5">Kelola Guru</p>
                    </Link>

                    <Link href="/admin/classes" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Data Kelas</h4>
                        <p className="text-xs text-slate-400 mt-0.5">Tingkat X, XI, XII</p>
                    </Link>

                    <Link href="/admin/departments" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Data Jurusan</h4>
                        <p className="text-xs text-slate-400 mt-0.5">7 Kompetensi</p>
                    </Link>

                    <Link href="/admin/schedules" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Jadwal Operasional</h4>
                        <p className="text-xs text-slate-400 mt-0.5">Absen & Sholat</p>
                    </Link>

                    <Link href="/admin/reports" className="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 hover:border-brand-500 transition-all text-center group shadow-sm">
                        <h4 className="font-semibold text-sm text-slate-900 dark:text-white group-hover:text-brand-600">Rekap Laporan</h4>
                        <p className="text-xs text-slate-400 mt-0.5">Cetak & Excel</p>
                    </Link>
                </div>

                {/* Department Stats */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="font-heading font-semibold text-base sm:text-lg text-slate-900 dark:text-white">
                            Absensi per Jurusan Hari Ini
                        </h3>
                        <span className="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800">
                            {deptCounts?.reduce((a, b) => a + b, 0) || 0} Hadir
                        </span>
                    </div>
                    <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2.5">
                        {deptLabels?.map((code, idx) => (
                            <div key={code} className="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-center">
                                <span className="text-xs font-bold text-brand-600 dark:text-brand-400 block uppercase">{code}</span>
                                <span className="text-base sm:text-lg font-bold text-slate-900 dark:text-white mt-1 block">
                                    {deptCounts[idx] || 0} <span className="text-xs font-normal text-slate-400">Siswa</span>
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
