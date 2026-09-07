import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function AdminSchedulesIndex({ schedules, prayerSchedules }) {
    const dailySchedule = schedules && schedules.length > 0 ? schedules[0] : null;
    const prayerSchedule = prayerSchedules && prayerSchedules.length > 0 ? prayerSchedules[0] : null;

    const [dailyForm, setDailyForm] = useState({
        start_time: dailySchedule?.start_time || '06:30:00',
        end_time: dailySchedule?.end_time || '07:15:00',
        late_time: dailySchedule?.late_time || '07:00:00',
        is_active: dailySchedule?.is_active ?? 1,
    });

    const [prayerForm, setPrayerForm] = useState({
        start_time: prayerSchedule?.start_time || '11:45:00',
        end_time: prayerSchedule?.end_time || '12:30:00',
        is_active: prayerSchedule?.is_active ?? 1,
    });

    const handleSaveDaily = (e) => {
        e.preventDefault();
        if (dailySchedule) {
            router.post(`/admin/schedules/${dailySchedule.id}/update`, dailyForm);
        }
    };

    const handleSavePrayer = (e) => {
        e.preventDefault();
        if (prayerSchedule) {
            router.post(`/admin/prayer-schedules/${prayerSchedule.id}/update`, prayerForm);
        }
    };

    return (
        <AppLayout
            headerTitle="Pengaturan Jam Operasional Absens"
            headerSubtitle="Atur batas jam absensi harian dan batas jam absensi sholat dzuhur"
        >
            <Head title="Pengaturan Jadwal Absensi" />

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Daily Schedule Card */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Jadwal Absensi Harian Pagi
                    </h3>

                    <form onSubmit={handleSaveDaily} className="space-y-4 text-xs">
                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Buka Absensi (Start):</label>
                            <input
                                type="time"
                                step="1"
                                value={dailyForm.start_time}
                                onChange={(e) => setDailyForm({ ...dailyForm, start_time: e.target.value })}
                                required
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Batas Toleransi Terlambat (Late):</label>
                            <input
                                type="time"
                                step="1"
                                value={dailyForm.late_time}
                                onChange={(e) => setDailyForm({ ...dailyForm, late_time: e.target.value })}
                                required
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Tutup Absensi (End):</label>
                            <input
                                type="time"
                                step="1"
                                value={dailyForm.end_time}
                                onChange={(e) => setDailyForm({ ...dailyForm, end_time: e.target.value })}
                                required
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div className="pt-2">
                            <button
                                type="submit"
                                className="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold shadow-md"
                            >
                                Simpan Jadwal Harian
                            </button>
                        </div>
                    </form>
                </div>

                {/* Prayer Schedule Card */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 className="font-heading font-semibold text-lg text-slate-900 dark:text-white mb-4">
                        Jadwal Absensi Sholat Dzuhur
                    </h3>

                    <form onSubmit={handleSavePrayer} className="space-y-4 text-xs">
                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Mulai Sholat (Start):</label>
                            <input
                                type="time"
                                step="1"
                                value={prayerForm.start_time}
                                onChange={(e) => setPrayerForm({ ...prayerForm, start_time: e.target.value })}
                                required
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div>
                            <label className="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Selesai Sholat (End):</label>
                            <input
                                type="time"
                                step="1"
                                value={prayerForm.end_time}
                                onChange={(e) => setPrayerForm({ ...prayerForm, end_time: e.target.value })}
                                required
                                className="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 font-medium text-slate-900 dark:text-white"
                            />
                        </div>

                        <div className="pt-2">
                            <button
                                type="submit"
                                className="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-md"
                            >
                                Simpan Jadwal Sholat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
