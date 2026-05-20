<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TermReport;
use App\Models\StudentEnrollment;
use App\Models\Sequence;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Mark;

class DashboardController extends Controller
{
    public function index()
    {
        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        $stats = [
            'students'   => Student::where('school_id', $school->id)->count(),
            'teachers'   => User::where('school_id', $school->id)->where('role', 'teacher')->where('is_active', true)->count(),
            'classrooms' => Classroom::where('school_id', $school->id)->where('academic_year_id', $currentYear?->id)->count(),
            'subjects'   => Subject::where('school_id', $school->id)->count(),
        ];

        // Class performance cards
        $classPerformance = [];
        if ($currentYear) {
            $classrooms = Classroom::where('school_id', $school->id)
                ->where('academic_year_id', $currentYear->id)
                ->with(['classLevel', 'stream'])
                ->get();

            foreach ($classrooms as $classroom) {
                $enrollmentIds = StudentEnrollment::where('classroom_id', $classroom->id)
                    ->where('academic_year_id', $currentYear->id)
                    ->pluck('id');

                $reports = TermReport::whereIn('enrollment_id', $enrollmentIds)->get();

                $studentCount = $enrollmentIds->count();
                $avgPerformance = $reports->isNotEmpty()
                    ? round($reports->avg('term_average'), 2)
                    : null;
                $passCount = $reports->where('term_average', '>=', 10)->count();
                $passRate  = $reports->isNotEmpty()
                    ? round(($passCount / $reports->count()) * 100)
                    : null;
                $highest = $reports->isNotEmpty() ? $reports->max('term_average') : null;

                // Sequence completion
                $sequences = Sequence::whereHas('term', fn($q) =>
                    $q->where('academic_year_id', $currentYear->id)
                )->get();

                $assignedTeachers = TeacherAssignment::where('classroom_id', $classroom->id)
                    ->where('academic_year_id', $currentYear->id)
                    ->count();

                $classPerformance[] = [
                    'classroom'       => $classroom,
                    'student_count'   => $studentCount,
                    'avg_performance' => $avgPerformance,
                    'pass_rate'       => $passRate,
                    'highest'         => $highest,
                    'assigned_teachers' => $assignedTeachers,
                    'has_reports'     => $reports->isNotEmpty(),
                ];
            }
        }

        // Sequence progress
$sequenceProgress = [];
if ($currentYear) {
    $sequences = Sequence::whereHas('term', fn($q) =>
        $q->where('academic_year_id', $currentYear->id)
    )->with('term')->orderBy('id')->get();

    $totalClassrooms = Classroom::where('school_id', $school->id)
        ->where('academic_year_id', $currentYear->id)
        ->count();

    $classroomIds = Classroom::where('school_id', $school->id)
        ->where('academic_year_id', $currentYear->id)
        ->pluck('id');

    $enrollmentIds = StudentEnrollment::whereIn('classroom_id', $classroomIds)
        ->pluck('id');

    foreach ($sequences as $sequence) {
        $classroomsWithMarks = Mark::where('sequence_id', $sequence->id)
            ->whereIn('enrollment_id', $enrollmentIds)
            ->distinct('enrollment_id')
            ->join('student_enrollments', 'marks.enrollment_id', '=', 'student_enrollments.id')
            ->distinct('student_enrollments.classroom_id')
            ->count('student_enrollments.classroom_id');

        $sequenceProgress[] = [
            'sequence'             => $sequence,
            'classrooms_submitted' => $classroomsWithMarks,
            'total_classrooms'     => $totalClassrooms,
            'complete'             => $totalClassrooms > 0 && $classroomsWithMarks >= $totalClassrooms,
        ];
    }
}

        return view('admin.dashboard', compact(
            'stats', 'currentYear', 'classPerformance', 'sequenceProgress'
        ));
    }
}