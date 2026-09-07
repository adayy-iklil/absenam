<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) return redirect()->route('admin.dashboard');
            if ($user->isTeacher()) return redirect()->route('teacher.dashboard');
            if ($user->isStudent()) return redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email / NIS / NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = trim($request->email);

        // 1. Direct Email lookup
        $user = User::where('email', $loginInput)->first();

        // 2. Student NIS lookup or User Email matching NIS
        if (!$user) {
            $student = \App\Models\Student::where('nis', $loginInput)->first();
            if ($student && $student->user) {
                $user = $student->user;
            } else {
                $user = User::where('email', 'LIKE', $loginInput . '@%')->first();
            }
        }

        // 3. Teacher NIP lookup
        if (!$user) {
            $teacher = \App\Models\Teacher::where('nip', $loginInput)->first();
            if ($teacher && $teacher->user) {
                $user = $teacher->user;
            }
        }

        if ($user) {
            // Password check: bcrypt hash first (primary), then plain text fallback
            $isPasswordValid = false;

            if (str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$2a$') || str_starts_with($user->password, '$2b$')) {
                // Bcrypt hash — use Hash::check
                $isPasswordValid = Hash::check($request->password, $user->password);
            } else {
                // Plain text fallback (legacy)
                $isPasswordValid = ($request->password === $user->password);
            }

            if ($isPasswordValid) {
                Auth::login($user, $request->has('remember'));
                $request->session()->regenerate();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'Login',
                    'description' => 'User ' . $user->name . ' berhasil login ke sistem.',
                    'ip_address' => $request->ip(),
                ]);

                if ($user->isAdmin()) return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang, Admin ' . $user->name);
                if ($user->isTeacher()) return redirect()->route('teacher.dashboard')->with('success', 'Selamat Datang, Bp/Ibu ' . $user->name);
                if ($user->isStudent()) return redirect()->route('student.dashboard')->with('success', 'Selamat Datang, ' . $user->name);
            }
        }

        return back()->withInput()->with('error', 'Kombinasi Email/NIS dan Password salah.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Logout',
                'description' => 'User ' . Auth::user()->name . ' keluar dari sistem.',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
