<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role' => $user->role ? $user->role->name : null,
                    'is_student' => $user->isStudent(),
                    'is_teacher' => $user->isTeacher(),
                    'is_admin' => $user->isAdmin(),
                    'student' => $user->isStudent() && $user->student ? [
                        'id' => $user->student->id,
                        'nis' => $user->student->nis,
                        'name' => $user->student->name,
                        'gender' => $user->student->gender,
                        'class_name' => $user->student->classModel ? $user->student->classModel->name : '-',
                        'department_name' => $user->student->department ? $user->student->department->name : '-',
                    ] : null,
                    'teacher' => $user->isTeacher() && $user->teacher ? [
                        'id' => $user->teacher->id,
                        'nip' => $user->teacher->nip,
                        'name' => $user->teacher->name,
                        'gender' => $user->teacher->gender,
                        'subject' => $user->teacher->subject,
                    ] : null,
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
