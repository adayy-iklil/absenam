<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        $isCurrentPasswordValid = false;

        if (str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$2a$') || str_starts_with($user->password, '$2b$')) {
            $isCurrentPasswordValid = Hash::check($request->current_password, $user->password);
        } else {
            $isCurrentPasswordValid = ($request->current_password === $user->password);
        }

        if (!$isCurrentPasswordValid) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();


        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Ganti Password',
            'description' => 'User ' . $user->name . ' berhasil memperbarui password akun.',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Password akun Anda berhasil diperbarui.');
    }
}
