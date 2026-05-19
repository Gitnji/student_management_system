<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Mark;
use App\Models\StudentEnrollment;
use App\Models\SubjectCoefficient;
use App\Models\Term;
use App\Models\TermReport;

class ReportGenerationService
{
    public function generateTermReports(Classroom $classroom, Term $term): array
{
    $sequences = $term->sequences()->orderBy('sequence_number')->get();

    if ($sequences->count() !== 2) {
        throw new \Exception("Term must have exactly 2 sequences before generating reports.");
    }

    $seq1 = $sequences->firstWhere('sequence_number', 1);
    $seq2 = $sequences->firstWhere('sequence_number', 2);

    $enrollments = StudentEnrollment::where('classroom_id', $classroom->id)
        ->where('academic_year_id', $term->academic_year_id)
        ->where('status', 'active')
        ->with('student')
        ->get();

    if ($enrollments->isEmpty()) {
        throw new \Exception("No active students in this classroom.");
    }

    $coefficients = SubjectCoefficient::where('class_level_id', $classroom->class_level_id)
        ->where('stream_id', $classroom->stream_id)
        ->with('subject')
        ->get()
        ->keyBy('subject_id');

    $enrollmentIds = $enrollments->pluck('id');
    $subjectIds    = $coefficients->keys();
    $sequenceIds   = [$seq1->id, $seq2->id];

    // Load ALL marks in one query
    $allMarks = Mark::whereIn('enrollment_id', $enrollmentIds)
        ->whereIn('subject_id', $subjectIds)
        ->whereIn('sequence_id', $sequenceIds)
        ->get()
        ->groupBy(fn($m) => "{$m->enrollment_id}_{$m->subject_id}_{$m->sequence_id}");

    $reportData = [];

    foreach ($enrollments as $enrollment) {
        $grandTotal      = 0;
        $totalCoef       = 0;
        $subjectsPassed  = 0;
        $subjectsFailed  = 0;
        $subjectDetails  = [];
        $seq1GrandTotal  = 0;
        $seq1TotalCoef   = 0;
        $seq2GrandTotal  = 0;
        $seq2TotalCoef   = 0;

        foreach ($coefficients as $subjectId => $coefModel) {
            $coef  = $coefModel->coefficient;
            $key1  = "{$enrollment->id}_{$subjectId}_{$seq1->id}";
            $key2  = "{$enrollment->id}_{$subjectId}_{$seq2->id}";
            $mark1 = $allMarks->get($key1)?->first()?->mark;
            $mark2 = $allMarks->get($key2)?->first()?->mark;

            if ($mark1 === null && $mark2 === null) continue;

            $mark1   = $mark1 ?? 0;
            $mark2   = $mark2 ?? 0;
            $termAvg = ($mark1 + $mark2) / 2;
            $total   = $termAvg * $coef;

            $grandTotal += $total;
            $totalCoef  += $coef;

            $seq1GrandTotal += $mark1 * $coef;
            $seq1TotalCoef  += $coef;
            $seq2GrandTotal += $mark2 * $coef;
            $seq2TotalCoef  += $coef;

            if ($termAvg >= 10) $subjectsPassed++;
            else $subjectsFailed++;

            $subjectDetails[] = [
                'subject_id'   => $subjectId,
                'subject_name' => $coefModel->subject->name,
                'subject_code' => $coefModel->subject->code,
                'coefficient'  => $coef,
                'seq1'         => $mark1,
                'seq2'         => $mark2,
                'term_avg'     => round($termAvg, 2),
                'total'        => round($total, 2),
                'remark'       => $this->remarkLetter($termAvg),
                'failed'       => $termAvg < 10,
            ];
        }

        $overallAverage = $totalCoef > 0 ? round($grandTotal / $totalCoef, 2) : 0;
        $seq1Average    = $seq1TotalCoef > 0 ? round($seq1GrandTotal / $seq1TotalCoef, 2) : 0;
        $seq2Average    = $seq2TotalCoef > 0 ? round($seq2GrandTotal / $seq2TotalCoef, 2) : 0;

        $reportData[$enrollment->id] = [
            'enrollment'       => $enrollment,
            'grand_total'      => round($grandTotal, 2),
            'total_coef'       => $totalCoef,
            'overall_avg'      => $overallAverage,
            'seq1_average'     => $seq1Average,
            'seq2_average'     => $seq2Average,
            'subjects_passed'  => $subjectsPassed,
            'subjects_failed'  => $subjectsFailed,
            'subject_details'  => $subjectDetails,
            'conduct_rating'   => $this->conductRating($overallAverage),
            'principal_remark' => $this->generateRemark(
                $enrollment->student->first_name,
                $overallAverage,
                $subjectsPassed,
                $subjectsFailed
            ),
        ];
    }

    $averages       = collect($reportData)->pluck('overall_avg');
    $classAverage   = round($averages->avg(), 2);
    $highestInClass = $averages->max();
    $classSize      = $enrollments->count();

    $ranked = collect($reportData)->sortByDesc('overall_avg')->values();
    foreach ($ranked as $position => $data) {
        $reportData[$data['enrollment']->id]['position'] = $position + 1;
    }

    foreach ($reportData as $enrollmentId => $data) {
        TermReport::updateOrCreate(
            [
                'enrollment_id' => $enrollmentId,
                'term_id'       => $term->id,
            ],
            [
                'seq1_average'             => $data['seq1_average'],
                'seq2_average'             => $data['seq2_average'],
                'term_average'             => $data['overall_avg'],
                'total_coefficient_points' => $data['grand_total'],
                'position_in_class'        => $data['position'],
                'class_size'               => $classSize,
                'class_average'            => $classAverage,
                'highest_in_class'         => $highestInClass,
                'conduct_rating'           => $data['conduct_rating'],
                'principal_remark'         => $data['principal_remark'],
                'generated_at'             => now(),
            ]
        );
    }

    return [
        'reports'       => $reportData,
        'class_average' => $classAverage,
        'highest'       => $highestInClass,
        'class_size'    => $classSize,
    ];
}

    private function sequenceAverage(StudentEnrollment $enrollment, $sequence, $coefficients): float
    {
        $grandTotal = 0;
        $totalCoef  = 0;

        foreach ($coefficients as $subjectId => $coefModel) {
            $mark = Mark::where('enrollment_id', $enrollment->id)
                ->where('subject_id', $subjectId)
                ->where('sequence_id', $sequence->id)
                ->value('mark');

            if ($mark === null) continue;

            $grandTotal += $mark * $coefModel->coefficient;
            $totalCoef  += $coefModel->coefficient;
        }

        return $totalCoef > 0 ? round($grandTotal / $totalCoef, 2) : 0;
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

    private function conductRating(float $avg): string
    {
        return match(true) {
            $avg >= 16 => 'excellent',
            $avg >= 12 => 'good',
            $avg >= 10 => 'average',
            default    => 'poor',
        };
    }

    private function generateRemark(string $firstName, float $avg, int $passed, int $failed): string
    {
        if ($avg >= 16) {
            return "{$firstName} has demonstrated excellent academic performance this term. Keep up the outstanding work and continue to aim higher.";
        }

        if ($avg >= 14) {
            return "{$firstName} has performed very well this term. With continued dedication, even greater results are achievable next term.";
        }

        if ($avg >= 12) {
            return "{$firstName} has shown satisfactory performance this term. More consistent effort across all subjects will lead to better results.";
        }

        if ($avg >= 10) {
            $failedText = $failed > 0 ? " Particular attention is needed in {$failed} subject(s) where performance was below average." : "";
            return "{$firstName} has achieved a passing average this term, though there is significant room for improvement.{$failedText}";
        }

        return "{$firstName} has not met the minimum academic standard this term. Serious and consistent effort is urgently required across all subjects to improve performance next term.";
    }
}