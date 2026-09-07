<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Department;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\PrayerSchedule;
use App\Models\Attendance;
use App\Models\PrayerAttendance;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();

        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();

        $countHadir = Attendance::where('date', $today)->where('status', 'Hadir')->count();
        $countTerlambat = Attendance::where('date', $today)->where('status', 'Terlambat')->count();
        $countIzin = Attendance::where('date', $today)->where('status', 'Izin')->count();
        $countSakit = Attendance::where('date', $today)->where('status', 'Sakit')->count();
        $countAlpha = Attendance::where('date', $today)->where('status', 'Alpha')->count();

        // Chart Data: Attendance by Department
        $departments = Department::withCount(['students'])->get();
        $deptLabels = [];
        $deptCounts = [];
        foreach ($departments as $dept) {
            $deptLabels[] = $dept->code;
            $deptCounts[] = Attendance::where('date', $today)
                ->whereHas('student', function ($q) use ($dept) {
                    $q->where('department_id', $dept->id);
                })
                ->whereIn('status', ['Hadir', 'Terlambat'])
                ->count();
        }

        // Chart Data: Monthly trends
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyHadir = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyHadir[] = Attendance::whereMonth('date', $m)->whereIn('status', ['Hadir', 'Terlambat'])->count();
        }

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'countHadir',
            'countTerlambat',
            'countIzin',
            'countSakit',
            'countAlpha',
            'deptLabels',
            'deptCounts',
            'months',
            'monthlyHadir'
        ));
    }

    // --- SISWA CRUD ---
    public function studentsIndex(Request $request)
    {
        $query = Student::with(['user', 'classModel', 'department']);

        if ($request->filled('class_id')) {
            $query->where('students.class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('students.nis', 'like', "%{$search}%")
                  ->orWhere('students.name', 'like', "%{$search}%");
            });
        }

        $students = $query->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*', 'classes.grade as class_grade', 'classes.name as class_name')
            ->orderByRaw("CASE classes.grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->paginate(15)->withQueryString();

        $classes = Classes::orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('name', 'asc')
            ->get();

        $departments = Department::orderBy('code', 'asc')->get();

        return view('admin.students.index', compact('students', 'classes', 'departments'));
    }


    public function studentsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|string|unique:students,nis',
            'password' => 'required|string|min:6',
            'class_id' => 'required|exists:classes,id',
            'department_id' => 'required|exists:departments,id',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
        ]);

        $role = Role::where('name', 'siswa')->first();

        $user = User::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Data Siswa SMKN 6 Jakarta Berhasil Ditambahkan.');
    }

    public function studentsUpdate(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $userId = $user ? $user->id : 0;

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $userId,
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'department_id' => 'required|exists:departments,id',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
        ]);

        $student->update([
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
        ]);

        if ($user) {
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }

        return back()->with('success', 'Data Siswa Berhasil Diperbarui.');
    }

    public function studentsDestroy($id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $student->delete();
        if ($user) $user->delete();

        return back()->with('success', 'Data Siswa Berhasil Dihapus.');
    }

    // --- GURU CRUD ---
    public function teachersIndex(Request $request)
    {
        $teachers = Teacher::with(['user', 'department', 'teacherClassModel'])->orderBy('name')->paginate(10)->withQueryString();
        $departments = Department::orderBy('code')->get();
        $classes = Classes::orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.teachers.index', compact('teachers', 'departments', 'classes'));
    }

    public function teachersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:teachers,nip',
            'password' => 'required|string|min:6',
            'department_id' => 'nullable',
            'teacher_class_id' => 'nullable',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
        ]);

        $role = Role::where('name', 'guru')->first();

        $user = User::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'name' => $request->name,
            'gender' => $request->gender,
            'department_id' => $request->filled('department_id') ? $request->department_id : null,
            'teacher_class_id' => $request->filled('teacher_class_id') ? $request->teacher_class_id : null,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Data Guru SMKN 6 Jakarta Berhasil Ditambahkan.');
    }

    public function teachersUpdate(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;
        $userId = $user ? $user->id : 0;

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $userId,
            'nip' => 'required|string|unique:teachers,nip,' . $teacher->id,
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
        ]);

        $teacher->update([
            'nip' => $request->nip,
            'name' => $request->name,
            'gender' => $request->gender,
            'department_id' => $request->filled('department_id') ? $request->department_id : null,
            'teacher_class_id' => $request->filled('teacher_class_id') ? $request->teacher_class_id : null,
            'phone' => $request->phone,
        ]);

        if ($user) {
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }

        return back()->with('success', 'Data Guru SMKN 6 Jakarta Berhasil Diperbarui.');
    }

    public function teachersDestroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;
        $teacher->delete();
        if ($user) $user->delete();

        return back()->with('success', 'Data Guru Berhasil Dihapus.');
    }

    // --- KELAS CRUD ---
    public function classesIndex()
    {
        $classes = Classes::with('department')
            ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('name', 'asc')
            ->get();
        $departments = Department::orderBy('code')->get();

        return view('admin.classes.index', compact('classes', 'departments'));
    }

    public function classesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'grade' => 'required|in:X,XI,XII',
        ]);

        Classes::create($request->all());
        return back()->with('success', 'Kelas Baru Berhasil Ditambahkan.');
    }

    public function classesUpdate(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'grade' => 'required|in:X,XI,XII',
        ]);
        $class->update($request->all());
        return back()->with('success', 'Data Kelas Berhasil Diperbarui.');
    }

    // --- JURUSAN CRUD ---
    public function departmentsIndex()
    {
        $departments = Department::withCount('students')->orderBy('code')->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function departmentsStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:departments,code',
            'name' => 'required|string|max:150',
        ]);

        Department::create($request->all());
        return back()->with('success', 'Jurusan Baru Berhasil Ditambahkan.');
    }

    public function departmentsUpdate(Request $request, $id)
    {
        $dept = Department::findOrFail($id);
        $request->validate([
            'code' => 'required|string|max:20|unique:departments,code,' . $dept->id,
            'name' => 'required|string|max:150',
        ]);
        $dept->update($request->all());
        return back()->with('success', 'Data Jurusan Berhasil Diperbarui.');
    }

    // --- SCHEDULE & PRAYER SCHEDULE ---
    public function schedulesIndex()
    {
        $schedules = Schedule::all();
        $prayerSchedules = PrayerSchedule::all();

        return view('admin.schedules.index', compact('schedules', 'prayerSchedules'));
    }

    public function schedulesUpdate(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->only(['start_time', 'end_time', 'late_time', 'is_active']));
        return back()->with('success', 'Jadwal Absensi Berhasil Diperbarui.');
    }

    public function prayerSchedulesUpdate(Request $request, $id)
    {
        $schedule = PrayerSchedule::findOrFail($id);
        $schedule->update($request->only(['start_time', 'end_time', 'is_active']));
        return back()->with('success', 'Jadwal Sholat Berhasil Diperbarui.');
    }

    // --- ANNOUNCEMENTS ---
    public function announcementsIndex()
    {
        $announcements = Announcement::with('author')->latest()->get();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function announcementsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:all,siswa,guru',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
            'author_id' => Auth::id(),
        ]);

        return back()->with('success', 'Pengumuman Berhasil Diterbitkan.');
    }

    // --- LAPORAN & EXPORT ---
    public function reports(Request $request)
    {
        $query = Attendance::with(['student.user', 'student.classModel', 'student.department', 'teacher.user']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest('date')->get();
        $classes = Classes::orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('name', 'asc')
            ->get();
        $departments = Department::orderBy('code')->get();

        if ($request->get('export') === 'excel') {
            return $this->exportExcel($attendances);
        }

        if ($request->get('export') === 'print' || $request->get('export') === 'pdf') {
            return view('admin.reports_print', compact('attendances'));
        }

        return view('admin.reports', compact('attendances', 'classes', 'departments'));
    }


    private function exportExcel($attendances)
    {
        $filename = "Laporan_Absensi_SMKN6_Jakarta_" . date('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Tanggal', 'Jam', 'NIS', 'Nama Siswa', 'Kelas', 'Jurusan', 'Status', 'Alamat / Nama Jalan', 'Verifikator']);

            foreach ($attendances as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->date,
                    $row->time,
                    $row->student->nis ?? '-',
                    $row->student->name ?? ($row->student->user->name ?? '-'),
                    $row->student->classModel->name ?? '-',
                    $row->student->department->code ?? '-',
                    $row->status,
                    $row->address,
                    $row->teacher->user->name ?? 'System Auto'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
