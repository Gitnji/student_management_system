<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $school        = Auth::user()->school;
        $currentYear   = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $academicYears = AcademicYear::where('school_id', $school->id)->orderByDesc('start_date')->get();

        $yearId = $request->get('year_id', $currentYear?->id);

        $assignments = TeacherAssignment::with(['teacher', 'classroom', 'subject'])
            ->whereHas('classroom', fn($q) => $q->where('school_id', $school->id))
            ->where('academic_year_id', $yearId)
            ->orderBy('classroom_id')
            ->get();

        return view('admin.teacher-assignments.index', compact('assignments', 'academicYears', 'yearId'));
    }

    public function create()
    {
        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();

        $classrooms = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $currentYear?->id)
            ->with(['classLevel', 'stream'])
            ->orderBy('name')
            ->get();

        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $subjects = Subject::where('school_id', $school->id)
            ->orderBy('name')
            ->get();

        return view('admin.teacher-assignments.create', compact('classrooms', 'teachers', 'subjects', 'currentYear'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'teacher_id'       => ['required', 'exists:users,id'],
            'classroom_id'     => ['required', 'exists:classrooms,id'],
            'subject_id'       => ['required', 'exists:subjects,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        // Verify classroom belongs to school
        $classroom = Classroom::findOrFail($data['classroom_id']);
        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        // Prevent duplicate assignment
        $exists = TeacherAssignment::where('teacher_id', $data['teacher_id'])
            ->where('classroom_id', $data['classroom_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('academic_year_id', $data['academic_year_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['subject_id' => 'This teacher is already assigned to this subject in this classroom.']);
        }

        // Prevent two teachers teaching same subject in same classroom
        $subjectTaken = TeacherAssignment::where('classroom_id', $data['classroom_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('academic_year_id', $data['academic_year_id'])
            ->exists();

        if ($subjectTaken) {
            return back()->withInput()
                ->withErrors(['subject_id' => 'Another teacher is already assigned to this subject in this classroom.']);
        }

        TeacherAssignment::create($data);

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assigned successfully.');
    }

    public function destroy(TeacherAssignment $teacherAssignment)
    {
        $classroom = $teacherAssignment->classroom;
        if ($classroom->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $teacherAssignment->delete();

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Assignment removed.');
    }
}