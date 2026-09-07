<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile & Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Student Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::post('/attendance/store', [StudentController::class, 'storeAttendance'])->name('attendance.store');
    Route::post('/prayer/store', [StudentController::class, 'storePrayerAttendance'])->name('prayer.store');
    Route::get('/history', [StudentController::class, 'history'])->name('history');
});

// Teacher Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [TeacherController::class, 'indexAttendance'])->name('attendance');
    Route::post('/attendance/{id}/confirm', [TeacherController::class, 'confirmAttendance'])->name('attendance.confirm');
    Route::get('/prayer', [TeacherController::class, 'indexPrayer'])->name('prayer');
    Route::post('/prayer/{id}/confirm', [TeacherController::class, 'confirmPrayer'])->name('prayer.confirm');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Students CRUD
    Route::get('/students', [AdminController::class, 'studentsIndex'])->name('students');
    Route::post('/students/store', [AdminController::class, 'studentsStore'])->name('students.store');
    Route::post('/students/{id}/update', [AdminController::class, 'studentsUpdate'])->name('students.update');
    Route::post('/students/{id}/delete', [AdminController::class, 'studentsDestroy'])->name('students.delete');
    Route::delete('/students/{id}', [AdminController::class, 'studentsDestroy'])->name('students.destroy');
    
    // Teachers CRUD
    Route::get('/teachers', [AdminController::class, 'teachersIndex'])->name('teachers');
    Route::post('/teachers/store', [AdminController::class, 'teachersStore'])->name('teachers.store');
    Route::post('/teachers/{id}/update', [AdminController::class, 'teachersUpdate'])->name('teachers.update');
    Route::post('/teachers/{id}/delete', [AdminController::class, 'teachersDestroy'])->name('teachers.delete');
    Route::delete('/teachers/{id}', [AdminController::class, 'teachersDestroy'])->name('teachers.destroy');
    
    // Classes & Departments
    Route::get('/classes', [AdminController::class, 'classesIndex'])->name('classes');
    Route::post('/classes/store', [AdminController::class, 'classesStore'])->name('classes.store');
    Route::post('/classes/{id}/update', [AdminController::class, 'classesUpdate'])->name('classes.update');
    Route::get('/departments', [AdminController::class, 'departmentsIndex'])->name('departments');
    Route::post('/departments/store', [AdminController::class, 'departmentsStore'])->name('departments.store');
    Route::post('/departments/{id}/update', [AdminController::class, 'departmentsUpdate'])->name('departments.update');
    
    // Schedules
    Route::get('/schedules', [AdminController::class, 'schedulesIndex'])->name('schedules');
    Route::post('/schedules/{id}/update', [AdminController::class, 'schedulesUpdate'])->name('schedules.update');
    Route::post('/prayer-schedules/{id}/update', [AdminController::class, 'prayerSchedulesUpdate'])->name('prayer-schedules.update');
    
    // Announcements
    Route::get('/announcements', [AdminController::class, 'announcementsIndex'])->name('announcements');
    Route::post('/announcements/store', [AdminController::class, 'announcementsStore'])->name('announcements.store');
    
    // Reports & Export
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
