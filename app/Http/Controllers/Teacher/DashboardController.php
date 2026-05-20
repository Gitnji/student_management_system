<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Sequence;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher     = Auth::user();
        $school      = $teacher->school;
        $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();

        $assignedClasses = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('academic_year_id', $currentYear?->id)
            ->distinct('classroom_id')
            ->count('classroom_id');

        $activeSequence = Sequence::whereHas('term.academicYear', fn($q) =>
            $q->where('school_id', $school->id)->where('is_current', true)
        )->where('is_locked', false)->orderBy('id')->first();

        return view('teacher.dashboard', compact('assignedClasses', 'activeSequence', 'currentYear'));
    }
}