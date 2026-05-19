<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class TeacherController extends Controller
{
    public function index()
    {
        $school   = Auth::user()->school;
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->orderBy('first_name')
            ->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Password::min(8)->letters()->numbers()],
        ]);

        $data['school_id']            = $school->id;
        $data['role']                 = 'teacher';
        $data['must_change_password'] = true;
        $data['is_active']            = true;

        User::create($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher account created successfully.');
    }

    public function edit(User $teacher)
    {
        $this->authorizeTeacher($teacher);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $this->authorizeTeacher($teacher);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email,' . $teacher->id],
            'password'   => ['nullable', Password::min(8)->letters()->numbers()],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['must_change_password'] = true;
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        $this->authorizeTeacher($teacher);

        if ($teacher->teacherAssignments()->exists()) {
            return back()->with('error', 'Cannot delete a teacher with class assignments. Deactivate them instead.');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted.');
    }

    public function toggleActive(User $teacher)
    {
        $this->authorizeTeacher($teacher);

        $teacher->update(['is_active' => !$teacher->is_active]);

        $status = $teacher->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Teacher {$status} successfully.");
    }

    private function authorizeTeacher(User $teacher): void
    {
        if ($teacher->school_id !== Auth::user()->school_id || $teacher->role !== 'teacher') {
            abort(403);
        }
    }
}