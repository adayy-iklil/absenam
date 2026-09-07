<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\PrayerAttendance;
use App\Models\Announcement;
use App\Models\Schedule;
use App\Models\PrayerSchedule;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            $defaultClass = \App\Models\Classes::first();
            $nis = is_numeric($user->email) ? $user->email : '999' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $student = \App\Models\Student::create([
                'user_id' => $user->id,
                'class_id' => $defaultClass ? $defaultClass->id : 1,
                'department_id' => 1,
                'nis' => $nis,
                'name' => $user->name,
                'gender' => 'L',
                'phone' => '08123456789',
            ]);
        }

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        $todayAttendance = Attendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        $todayPrayerAttendance = PrayerAttendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        // Attendance stats counters
        $countHadir = Attendance::where('student_id', $student->id)->where('status', 'Hadir')->count();
        $countTerlambat = Attendance::where('student_id', $student->id)->where('status', 'Terlambat')->count();
        $countIzin = Attendance::where('student_id', $student->id)->where('status', 'Izin')->count();
        $countSakit = Attendance::where('student_id', $student->id)->where('status', 'Sakit')->count();
        $countAlpha = Attendance::where('student_id', $student->id)->where('status', 'Alpha')->count();

        // Prayer Window Check (11:45 - 12:30 WIB)
        $now = Carbon::now('Asia/Jakarta');
        $prayerSchedule = PrayerSchedule::where('is_active', 1)->first();
        $prayerStartTime = $prayerSchedule ? Carbon::createFromTimeString($prayerSchedule->start_time, 'Asia/Jakarta') : Carbon::createFromTimeString('11:45:00', 'Asia/Jakarta');
        $prayerEndTime = $prayerSchedule ? Carbon::createFromTimeString($prayerSchedule->end_time, 'Asia/Jakarta') : Carbon::createFromTimeString('12:30:00', 'Asia/Jakarta');

        $prayerStatusMsg = '';
        $canDoPrayer = false;

        if ($now->lt($prayerStartTime)) {
            $prayerStatusMsg = 'Absensi Sholat Belum Dibuka (Buka Pukul ' . $prayerStartTime->format('H:i') . ' WIB)';
        } elseif ($now->gt($prayerEndTime)) {
            $prayerStatusMsg = 'Waktu Absensi Sholat Telah Berakhir (Tutup Pukul ' . $prayerEndTime->format('H:i') . ' WIB)';
        } else {
            $canDoPrayer = true;
            $prayerStatusMsg = 'Absensi Sholat Sedang Dibuka';
        }

        // Monthly Calendar Matrix Generation
        $currentMonth = Carbon::now('Asia/Jakarta')->month;
        $currentYear = Carbon::now('Asia/Jakarta')->year;
        $daysInMonth = Carbon::now('Asia/Jakarta')->daysInMonth;

        $monthAttendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->day;
            });

        $calendarDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day, 'Asia/Jakarta');
            $isWeekend = $date->isWeekend(); // Saturday & Sunday = Libur
            $att = $monthAttendances->get($day);

            $calendarDays[] = [
                'day' => $day,
                'date_str' => $date->format('d M Y'),
                'day_name' => $date->isoFormat('dd'),
                'is_weekend' => $isWeekend,
                'status' => $att ? $att->status : ($isWeekend ? 'Libur' : ($date->isPast() ? 'Belum/Alpha' : 'Belum')),
                'time' => $att ? $att->time : null,
                'address' => $att ? $att->address : null,
            ];
        }

        $announcements = Announcement::whereIn('target_role', ['all', 'siswa'])
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'todayAttendance',
            'todayPrayerAttendance',
            'countHadir',
            'countTerlambat',
            'countIzin',
            'countSakit',
            'countAlpha',
            'canDoPrayer',
            'prayerStatusMsg',
            'calendarDays',
            'announcements'
        ));
    }


    public function storeAttendance(Request $request)
    {
        $request->validate([
            'photo' => 'required|string',
            'status' => 'nullable|in:Hadir,Sakit,Izin,Alpha',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $student = $user->student;
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $existing = Attendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi harian hari ini (' . $existing->status . ').'
            ], 400);
        }

        $selectedStatus = $request->status ?? 'Hadir';

        // Auto check if late when student submits "Hadir"
        if ($selectedStatus == 'Hadir') {
            $schedule = Schedule::where('is_active', 1)->first();
            $lateTime = $schedule ? $schedule->late_time : '07:00:00';
            if ($currentTime > $lateTime) {
                $finalStatus = 'Terlambat';
            } else {
                $finalStatus = 'Hadir';
            }
        } else {
            $finalStatus = $selectedStatus;
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'time' => $currentTime,
            'timestamp' => $now,
            'photo' => $request->photo,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address ?? 'Jl. Mahakam No.2, Kramat Pela, Kebayoran Baru, Jakarta Selatan (SMKN 6 Jakarta)',
            'status' => $finalStatus,
            'teacher_notes' => $request->notes,
            'device' => $request->header('User-Agent'),
            'browser' => $request->browser_name ?? 'Web Browser',
            'ip_address' => $request->ip(),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Absensi Harian',
            'description' => 'Siswa ' . $user->name . ' melakukan absensi dengan status: ' . $finalStatus,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi ' . $finalStatus . ' berhasil dikirim!',
            'data' => $attendance
        ]);
    }

    public function storePrayerAttendance(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $prayerSchedule = PrayerSchedule::where('is_active', 1)->first();
        $prayerStartTime = $prayerSchedule ? Carbon::createFromTimeString($prayerSchedule->start_time, 'Asia/Jakarta') : Carbon::createFromTimeString('11:45:00', 'Asia/Jakarta');
        $prayerEndTime = $prayerSchedule ? Carbon::createFromTimeString($prayerSchedule->end_time, 'Asia/Jakarta') : Carbon::createFromTimeString('12:30:00', 'Asia/Jakarta');

        if ($now->lt($prayerStartTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu Absensi Sholat Belum Dimulai (11.45 WIB).'
            ], 400);
        }

        if ($now->gt($prayerEndTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu Absensi Sholat Telah Berakhir (12.30 WIB).'
            ], 400);
        }

        $request->validate([
            'photo' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        $existing = PrayerAttendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi sholat hari ini.'
            ], 400);
        }

        $prayerAttendance = PrayerAttendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'time' => $now->format('H:i:s'),
            'timestamp' => $now,
            'photo' => $request->photo,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address ?? 'Masjid SMKN 6 Jakarta',
            'status' => 'Hadir',
            'device' => $request->header('User-Agent'),
            'browser' => $request->browser_name ?? 'Web Browser',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi Sholat Berhasil Dikirim!',
            'data' => $prayerAttendance
        ]);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $month = $request->get('month', Carbon::now('Asia/Jakarta')->month);
        $year = $request->get('year', Carbon::now('Asia/Jakarta')->year);

        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->latest('date')
            ->get();

        $prayerAttendances = PrayerAttendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->latest('date')
            ->get();

        return view('student.history', compact(
            'student',
            'attendances',
            'prayerAttendances',
            'month',
            'year'
        ));
    }

}
