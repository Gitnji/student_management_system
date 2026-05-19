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

class MarkController extends Controller
{
    public function index()
    {
        $teacher     = Auth::user();
        $school      = $teacher->school;
        $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();

        $assignments = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('academic_year_id', $currentYear?->id)
            ->with(['classroom.classLevel', 'classroom.stream', 'subject'])
            ->get()
            ->groupBy('classroom_id');

        $sequences = Sequence::whereHas('term.academicYear', fn($q) => $q->where('school_id', $school->id))
            ->whereHas('term', fn($q) => $q->whereHas('academicYear', fn($q2) => $q2->where('is_current', true)))
            ->with('term')
            ->orderBy('id')
            ->get();

            //wait
            $assignmentsJson = $assignments->map(function ($classAssignments) {
               return $classAssignments->map(function ($a) {
                return [
                   'subject_id'   => $a->subject_id,
                   'subject_name' => $a->subject->name,
                   'subject_code' => $a->subject->code,
              ];
    })->values();
});

return view('teacher.marks.index', compact('assignments', 'sequences', 'currentYear', 'assignmentsJson'));

        //return view('teacher.marks.index', compact('assignments', 'sequences', 'currentYear'));   
    }

    public function enter(Request $request)
    {
        $teacher = Auth::user();
        $school  = $teacher->school;

        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id'   => ['required', 'exists:subjects,id'],
            'sequence_id'  => ['required', 'exists:sequences,id'],
        ]);

        // Verify teacher is assigned to this class + subject
        $assignment = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        if (!$assignment) {
            abort(403, 'You are not assigned to this subject in this classroom.');
        }

        // Verify sequence is not locked
        $sequence = Sequence::with('term')->findOrFail($request->sequence_id);

        if ($sequence->is_locked) {
            return back()->with('error', 'This sequence is locked. Contact the administrator.');
        }

        // Get enrolled students
        $enrollments = StudentEnrollment::where('classroom_id', $request->classroom_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('status', 'active')
            ->with('student')
            ->get()
            ->sortBy('student.last_name');

        // Get existing marks
        $existingMarks = Mark::where('sequence_id', $request->sequence_id)
            ->whereIn('enrollment_id', $enrollments->pluck('id'))
            ->where('subject_id', $request->subject_id)
            ->get()
            ->keyBy('enrollment_id');

        $classroom = $assignment->classroom;
        $subject   = $assignment->subject;

        return view('teacher.marks.enter', compact(
            'enrollments', 'existingMarks', 'sequence',
            'classroom', 'subject', 'assignment'
        ));
    }

    public function save(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id'   => ['required', 'exists:subjects,id'],
            'sequence_id'  => ['required', 'exists:sequences,id'],
            'marks'        => ['required', 'array'],
            'marks.*'      => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        // Verify assignment
        $assignment = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        if (!$assignment) {
            abort(403);
        }

        // Verify sequence not locked
        $sequence = Sequence::findOrFail($request->sequence_id);
        if ($sequence->is_locked) {
            return back()->with('error', 'This sequence is locked.');
        }

        // Save marks
        foreach ($request->marks as $enrollmentId => $mark) {
            if ($mark === null || $mark === '') {
                // Delete mark if cleared
                Mark::where('enrollment_id', $enrollmentId)
                    ->where('subject_id', $request->subject_id)
                    ->where('sequence_id', $request->sequence_id)
                    ->delete();
                continue;
            }

            Mark::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'subject_id'    => $request->subject_id,
                    'sequence_id'   => $request->sequence_id,
                ],
                [
                    'mark'       => $mark,
                    'created_by' => $teacher->id,
                ]
            );
        }

        return redirect()->route('teacher.marks.index')
            ->with('success', "Marks saved for {$sequence->name}.");
    }
}