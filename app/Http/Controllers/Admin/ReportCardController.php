<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Term;
use App\Models\TermReport;
use App\Services\ReportGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mark;
use App\Models\SubjectCoefficient;
//use App\Models\TermReport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $school        = Auth::user()->school;
        $currentYear   = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $academicYears = AcademicYear::where('school_id', $school->id)->orderByDesc('start_date')->get();
        $yearId        = $request->get('year_id', $currentYear?->id);

        $classrooms = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $yearId)
            ->with(['classLevel', 'stream'])
            ->orderBy('name')
            ->get();

        $terms = Term::where('academic_year_id', $yearId)
            ->orderBy('term')
            ->get();

        return view('admin.report-cards.index', compact('classrooms', 'terms', 'academicYears', 'yearId'));
    }

    public function generate(Request $request, ReportGenerationService $service)
    {
        $school = Auth::user()->school;

        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'term_id'      => ['required', 'exists:terms,id'],
        ]);

        $classroom = Classroom::findOrFail($request->classroom_id);
        $term      = Term::findOrFail($request->term_id);

        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        try {
            $result = $service->generateTermReports($classroom, $term);

            return redirect()->route('admin.report-cards.index')
                ->with('success', "Reports generated for {$classroom->name} — {$term->term_name}. {$result['class_size']} students processed.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'term_id'      => ['required', 'exists:terms,id'],
        ]);

        $classroom = Classroom::findOrFail($request->classroom_id);
        $term      = Term::with('academicYear')->findOrFail($request->term_id);

        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        $sequences = $term->sequences()->orderBy('sequence_number')->get();
        $seq1      = $sequences->firstWhere('sequence_number', 1);
        $seq2      = $sequences->firstWhere('sequence_number', 2);

        $reports = TermReport::where('term_id', $term->id)
            ->whereHas('enrollment', fn($q) => $q->where('classroom_id', $classroom->id))
            ->with(['enrollment.student'])
            ->orderBy('position_in_class')
            ->get();

        return view('admin.report-cards.show', compact('classroom', 'term', 'reports', 'seq1', 'seq2', 'school'));
    }

    public function pdf(Request $request)
{
    $school = Auth::user()->school;

    $request->validate([
        'enrollment_id' => ['required', 'exists:student_enrollments,id'],
        'term_id'       => ['required', 'exists:terms,id'],
    ]);

    $term      = Term::with('academicYear')->findOrFail($request->term_id);
    $sequences = $term->sequences()->orderBy('sequence_number')->get();
    $seq1      = $sequences->firstWhere('sequence_number', 1);
    $seq2      = $sequences->firstWhere('sequence_number', 2);

    $enrollment = \App\Models\StudentEnrollment::with(['student', 'classroom.classLevel', 'classroom.stream'])
        ->findOrFail($request->enrollment_id);

    if ($enrollment->classroom->school_id !== $school->id) {
        abort(403);
    }

    $report = TermReport::where('enrollment_id', $enrollment->id)
        ->where('term_id', $term->id)
        ->firstOrFail();

    // Get subject details with marks
    $coefficients = SubjectCoefficient::where('class_level_id', $enrollment->classroom->class_level_id)
        ->where('stream_id', $enrollment->classroom->stream_id)
        ->with('subject')
        ->get()
        ->keyBy('subject_id');

    $allMarks = Mark::where('enrollment_id', $enrollment->id)
        ->whereIn('subject_id', $coefficients->keys())
        ->whereIn('sequence_id', [$seq1->id, $seq2->id])
        ->get()
        ->groupBy(fn($m) => "{$m->subject_id}_{$m->sequence_id}");

    $subjectRows = [];
    foreach ($coefficients as $subjectId => $coefModel) {
        $mark1 = $allMarks->get("{$subjectId}_{$seq1->id}")?->first()?->mark;
        $mark2 = $allMarks->get("{$subjectId}_{$seq2->id}")?->first()?->mark;

        if ($mark1 === null && $mark2 === null) continue;

        $mark1   = $mark1 ?? 0;
        $mark2   = $mark2 ?? 0;
        $termAvg = round(($mark1 + $mark2) / 2, 2);
        $total   = round($termAvg * $coefModel->coefficient, 2);

        $subjectRows[] = [
            'name'        => $coefModel->subject->name,
            'code'        => $coefModel->subject->code,
            'coefficient' => $coefModel->coefficient,
            'seq1'        => $mark1,
            'seq2'        => $mark2,
            'total'       => $total,
            'avg'         => $termAvg,
            'remark'      => $this->remarkLetter($termAvg),
            'failed'      => $termAvg < 10,
        ];
    }

    $pdf = Pdf::loadView('admin.report-cards.pdf', compact(
        'enrollment', 'report', 'term', 'subjectRows', 'school', 'seq1', 'seq2'
    ))->setPaper('a4', 'portrait');

    $filename = 'report-card-' . $enrollment->student->matricule . '-' . str_replace(' ', '-', $term->term_name) . '.pdf';

    return $pdf->download($filename);
}

private function remarkLetter(float $avg): string
{
    return match(true) {
        $avg >= 18 => 'A',
        $avg >= 15 => 'B',
        $avg >= 12 => 'C',
        $avg >= 10 => 'D',
        default    => 'E',
    };
}
}