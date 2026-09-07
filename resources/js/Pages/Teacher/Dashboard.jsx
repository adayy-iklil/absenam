import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Swal from 'sweetalert2';

export default function TeacherDashboard({
    teacher,
    countHadir = 0,
    countPending = 0,
    countTerlambat = 0,
    countTidakHadir = 0,
    weeklyDays = [],
    weeklyHadir = [],
    weeklyTerlambat = [],
    weeklyPending = [],
    pendingAttendances = [],
}) {
    const handleConfirm = (id, status) => {
        Swal.fire({
            title: 'Konfirmasi Absensi',
            text: `Apakah Anda yakin ingin mengubah status menjadi ${status}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal',
        }).then((res) => {
            if (res.isConfirmed) {
                router.post(`/guru/attendance/${id}/confirm`, { status });
            }
        });
    };

    return (
        <AppLayout
            headerTitle="Dashboard Guru / Wali Kelas"
            headerSubtitle={`Selamat Datang, ${teacher?.name || 'Bapak/Ibu Guru'}`}
        >
            <Head title="Dashboard Guru - SIX-PRESENCE SMKN 6 Jakarta" />

            <div className="space-y-6">
                {teacher && teacher.teacherClassModel && (
                    <div className="p-4 rounded-2xl bg-emerald-600 text-white shadow-lg flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-bold text-xl">
                                <i className="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-extrabold tracking-wider text-emerald-100">Penugasan Resmi</span>
                                <h3 className="text-lg font-black leading-tight">Wali Kelas {teacher.teacherClassModel.name}</h3>
                            </div>
                        </div>
                        <Link href="/guru/attendance?filter=wali_kelas" className="px-4 py-2 rounded-xl bg-white text-emerald-800 font-bold text-xs hover:bg-emerald-50 transition-colors shadow">
                            Khusus Kelas {teacher.teacherClassModel.name} →
                        </Link>
                    </div>
                )}

                {/* Stats Counters */}
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
                        <div>
                            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Hadir Hari Ini</p>
                            <h3 className="text-3xl font-black text-slate-900 dark:text-white mt-1">{countHadir}</h3>
                            <p className="text-[11px] text-emerald-500 font-semibold mt-1"><i className="fa-solid fa-circle-check"></i> Tervalidasi</p>
                        </div>
                        <div className="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-2xl font-bold">
                            <i className="fa-solid fa-user-check"></i>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
                        <div>
                            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikonfirmasi</p>
                            <h3 className="text-3xl font-black text-amber-500 mt-1">{countPending}</h3>
                            <p className="text-[11px] text-amber-500 font-semibold mt-1"><i className="fa-solid fa-clock-rotate-left"></i> Membutuhkan Respon</p>
                        </div>
                        <div className="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-300 flex items-center justify-center text-2xl font-bold">
                            <i className="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
                        <div>
                            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Terlambat</p>
                            <h3 className="text-3xl font-black text-brand-600 mt-1">{countTerlambat}</h3>
                            <p className="text-[11px] text-brand-500 font-semibold mt-1"><i className="fa-solid fa-business-time"></i> Melewati 07:00</p>
                        </div>
                        <div className="w-14 h-14 rounded-2xl bg-brand-100 dark:bg-brand-950 text-brand-600 dark:text-brand-300 flex items-center justify-center text-2xl font-bold">
                            <i className="fa-solid fa-user-clock"></i>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-sm">
                        <div>
                            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Tidak Hadir / Alpha</p>
                            <h3 className="text-3xl font-black text-rose-500 mt-1">{countTidakHadir}</h3>
                            <p className="text-[11px] text-rose-500 font-semibold mt-1"><i className="fa-solid fa-triangle-exclamation"></i> Belum Melakukan Absen</p>
                        </div>
                        <div className="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-300 flex items-center justify-center text-2xl font-bold">
                            <i className="fa-solid fa-user-xmark"></i>
                        </div>
                    </div>
                </div>

                {/* Pending Confirmation Table */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div className="flex items-center justify-between mb-4">
                        <div>
                            <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                <i className="fa-solid fa-user-check text-amber-500"></i> Antrean Absensi Perlu Konfirmasi Guru
                            </h3>
                            <p className="text-xs text-slate-500">Daftar absensi siswa yang menunggu konfirmasi Wali Kelas.</p>
                        </div>
                        <Link href="/guru/attendance" className="text-xs font-bold text-brand-600 hover:underline">
                            Lihat Semua →
                        </Link>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase">
                                    <th className="py-3 px-4">Nama Siswa / NIS</th>
                                    <th className="py-3 px-4">Kelas</th>
                                    <th className="py-3 px-4">Waktu</th>
                                    <th className="py-3 px-4">Alamat</th>
                                    <th className="py-3 px-4 text-center">Aksi Konfirmasi Guru</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                {pendingAttendances && pendingAttendances.length > 0 ? (
                                    pendingAttendances.map((att) => (
                                        <tr key={att.id} className="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                            <td className="py-3 px-4">
                                                <div className="font-extrabold text-slate-900 dark:text-white">{att.student?.name || att.student?.user?.name || '-'}</div>
                                                <div className="text-[11px] text-slate-400 font-mono">NIS: {att.student?.nis || '-'}</div>
                                            </td>
                                            <td className="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                                {att.student?.class_model?.name || '-'}
                                            </td>
                                            <td className="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">
                                                {att.time} WIB
                                            </td>
                                            <td className="py-3 px-4 max-w-[200px] truncate font-bold text-slate-700 dark:text-slate-300">
                                                <i className="fa-solid fa-road text-brand-600"></i> {att.address}
                                            </td>
                                            <td className="py-3 px-4 text-center">
                                                <div className="flex items-center justify-center gap-1.5">
                                                    <button
                                                        onClick={() => handleConfirm(att.id, 'Hadir')}
                                                        className="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20"
                                                    >
                                                        <i className="fa-solid fa-check mr-1"></i> Setujui
                                                    </button>
                                                    <button
                                                        onClick={() => handleConfirm(att.id, 'Terlambat')}
                                                        className="px-3 py-1.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md shadow-cyan-500/20"
                                                    >
                                                        <i className="fa-solid fa-clock mr-1"></i> Terlambat
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="5" className="py-6 text-center text-slate-400 font-medium">
                                            Tidak ada absensi yang menunggu konfirmasi saat ini.
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
