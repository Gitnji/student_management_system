<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Mark;
use App\Models\Sequence;
use App\Models\StudentEnrollment;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $teacher     = Auth::user();
        $school      = $teacher->school;
        $currentYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        $assignments = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('academic_year_id', $currentYear?->id)
            ->with(['classroom.classLevel', 'classroom.stream', 'subject'])
            ->get()
            ->groupBy('classroom_id');

        return view('teacher.classes.index', compact('assignments', 'currentYear'));
    }

    public function show(Request $request, int $classroomId)
{
    $teacher     = Auth::user();
    $school      = $teacher->school;
    $currentYear = AcademicYear::where('school_id', $school->id)
        ->where('is_current', true)
        ->first();

    $assignments = TeacherAssignment::where('teacher_id', $teacher->id)
        ->where('classroom_id', $classroomId)
        ->where('academic_year_id', $currentYear?->id)
        ->with(['subject', 'classroom.classLevel', 'classroom.stream'])
        ->get();

    if ($assignments->isEmpty()) {
        abort(403, 'You are not assigned to this classroom.');
    }

    $classroom = $assignments->first()->classroom;

    $enrollments = StudentEnrollment::where('classroom_id', $classroomId)
        ->where('academic_year_id', $currentYear?->id)
        ->where('status', 'active')
        ->with('student')
        ->get()
        ->sortBy('student.last_name');

    $sequences = Sequence::whereHas('term', fn($q) =>
        $q->where('academic_year_id', $currentYear?->id)
    )->orderBy('id')->get();

    $enrollmentIds = $enrollments->pluck('id');
    $subjectIds    = $assignments->pluck('subject_id');
    $sequenceIds   = $sequences->pluck('id');

    // Load ALL marks in one query
    $allMarks = Mark::whereIn('enrollment_id', $enrollmentIds)
        ->whereIn('subject_id', $subjectIds)
        ->whereIn('sequence_id', $sequenceIds)
        ->get();

    // Build matrix from in-memory collection — zero extra queries
    $markMatrix = [];
    foreach ($assignments as $assignment) {
        foreach ($sequences as $sequence) {
            $entered = $allMarks
                ->where('subject_id', $assignment->subject_id)
                ->where('sequence_id', $sequence->id)
                ->count();

            $markMatrix[$assignment->subject_id][$sequence->id] = [
                'entered' => $entered,
                'total'   => $enrollmentIds->count(),
                'locked'  => $sequence->is_locked,
            ];
        }
    }

    return view('teacher.classes.show', compact(
        'classroom', 'assignments', 'enrollments',
        'sequences', 'markMatrix', 'currentYear'
    ));
}
}