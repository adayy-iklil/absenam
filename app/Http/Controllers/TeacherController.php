<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\PrayerAttendance;
use App\Models\Classes;
use App\Models\Department;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();
        $teacher = Auth::user()->teacher;

        $countHadir = Attendance::where('date', $today)->where('status', 'Hadir')->count();
        $countPending = Attendance::where('date', $today)->where('status', 'Menunggu Konfirmasi')->count();
        $countTerlambat = Attendance::where('date', $today)->where('status', 'Terlambat')->count();
        $countTidakHadir = Student::count() - ($countHadir + $countPending + $countTerlambat);
        if ($countTidakHadir < 0) $countTidakHadir = 0;

        // Weekly chart statistics
        $weeklyDays = [];
        $weeklyHadir = [];
        $weeklyTerlambat = [];
        $weeklyPending = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dateStr = $day->toDateString();
            $weeklyDays[] = $day->isoFormat('dddd');
            $weeklyHadir[] = Attendance::where('date', $dateStr)->where('status', 'Hadir')->count();
            $weeklyTerlambat[] = Attendance::where('date', $dateStr)->where('status', 'Terlambat')->count();
            $weeklyPending[] = Attendance::where('date', $dateStr)->where('status', 'Menunggu Konfirmasi')->count();
        }

        // Pending Attendances (Exclusively for Wali Kelas assigned class if assigned)
        $query = Attendance::with(['student.user', 'student.classModel'])
            ->where('date', $today)
            ->where('status', 'Menunggu Konfirmasi');

        if ($teacher && $teacher->teacher_class_id) {
            $query->whereHas('student', function ($q) use ($teacher) {
                $q->where('class_id', $teacher->teacher_class_id);
            });
        }

        $pendingAttendances = $query->latest('time')->take(10)->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'countHadir',
            'countPending',
            'countTerlambat',
            'countTidakHadir',
            'weeklyDays',
            'weeklyHadir',
            'weeklyTerlambat',
            'weeklyPending',
            'pendingAttendances'
        ));
    }

    public function indexAttendance(Request $request)
    {
        $teacher = Auth::user()->teacher;
        $query = Attendance::with(['student.user', 'student.classModel', 'student.department']);

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        } else {
            $query->where('date', Carbon::today()->toDateString());
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        } elseif ($teacher && $teacher->teacher_class_id) {
            $query->whereHas('student', function ($q) use ($teacher) {
                $q->where('class_id', $teacher->teacher_class_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $attendances = $query->latest('time')->paginate(15)->withQueryString();
        $classes = Classes::all();

        return view('teacher.attendance_list', compact('attendances', 'classes', 'teacher'));
    }


    // STRICT WALI KELAS ENFORCEMENT FOR CONFIRMATION
    public function confirmAttendance(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Terlambat,Ditolak,Izin,Sakit',
            'teacher_notes' => 'nullable|string|max:500',
        ]);

        $teacher = Auth::user()->teacher;

        // Check 1: Is user assigned as Wali Kelas?
        if (!$teacher || !$teacher->teacher_class_id) {
            return back()->with('error', 'Akses Ditolak: Hanya Guru yang memiliki penugasan Wali Kelas yang berhak melakukan konfirmasi absensi.');
        }

        $attendance = Attendance::with('student')->findOrFail($id);

        // Check 2: Is student in the Wali Kelas's assigned class?
        if ($attendance->student->class_id != $teacher->teacher_class_id) {
            $assignedClassName = $teacher->teacherClassModel->name ?? 'binaan Anda';
            return back()->with('error', 'Akses Ditolak: Anda hanya berhak mengonfirmasi absensi siswa di kelas ' . $assignedClassName . '.');
        }

        $attendance->update([
            'status' => $request->status,
            'teacher_notes' => $request->teacher_notes,
            'teacher_id' => $teacher->id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Konfirmasi Wali Kelas',
            'description' => 'Wali Kelas ' . Auth::user()->name . ' mengonfirmasi absensi siswa ' . ($attendance->student->name ?? '') . ' menjadi ' . $request->status,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Status absensi siswa kelas binaan berhasil dikonfirmasi.');
    }

    public function indexPrayer(Request $request)
    {
        $teacher = Auth::user()->teacher;
        $query = PrayerAttendance::with(['student.user', 'student.classModel']);

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        } else {
            $query->where('date', Carbon::today()->toDateString());
        }

        if ($teacher && $teacher->teacher_class_id) {
            $query->whereHas('student', function ($q) use ($teacher) {
                $q->where('class_id', $teacher->teacher_class_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prayerAttendances = $query->latest('time')->paginate(15)->withQueryString();

        return view('teacher.prayer_list', compact('prayerAttendances', 'teacher'));
    }


    public function confirmPrayer(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Ditolak',
            'teacher_notes' => 'nullable|string|max:500',
        ]);

        $teacher = Auth::user()->teacher;

        if (!$teacher || !$teacher->teacher_class_id) {
            return back()->with('error', 'Akses Ditolak: Hanya Wali Kelas yang berhak melakukan konfirmasi absensi sholat.');
        }

        $prayerAttendance = PrayerAttendance::with('student')->findOrFail($id);

        if ($prayerAttendance->student->class_id != $teacher->teacher_class_id) {
            return back()->with('error', 'Akses Ditolak: Anda hanya berhak mengonfirmasi absensi sholat siswa di kelas binaan Anda.');
        }

        $prayerAttendance->update([
            'status' => $request->status,
            'teacher_notes' => $request->teacher_notes,
            'teacher_id' => $teacher->id,
        ]);

        return back()->with('success', 'Konfirmasi Absensi Sholat Berhasil Disimpan.');
    }
}
