<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassLevel;
use App\Models\Promotion;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\TermReport;
use Illuminate\Support\Facades\Auth;

class PromotionService
{
    public function computePromotions(AcademicYear $year): array
    {
        $school      = Auth::user()->school;
        $classrooms  = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $year->id)
            ->with(['classLevel'])
            ->get();

        $results = [];

        foreach ($classrooms as $classroom) {
            $enrollments = StudentEnrollment::where('classroom_id', $classroom->id)
                ->where('academic_year_id', $year->id)
                ->where('status', 'active')
                ->with('student')
                ->get();

            foreach ($enrollments as $enrollment) {
                // Get all term reports for this enrollment
                $termReports = TermReport::whereHas('term', fn($q) => $q->where('academic_year_id', $year->id))
                    ->where('enrollment_id', $enrollment->id)
                    ->get();

                if ($termReports->isEmpty()) continue;

                // Annual average = sum of term averages / number of terms
                $annualAverage = round($termReports->avg('term_average'), 2);

                // Determine decision
                $isUpperSix = $classroom->classLevel->name === 'Upper 6';
                $decision   = match(true) {
                    $isUpperSix     => 'graduated',
                    $annualAverage >= 10 => 'promoted',
                    default         => 'repeated',
                };

                // Get or create promotion record
                $promotion = Promotion::updateOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    [
                        'annual_average' => $annualAverage,
                        'decision'       => $decision,
                        'confirmed_by'   => null,
                        'confirmed_at'   => null,
                        'next_enrollment_id' => null,
                    ]
                );

                $results[] = [
                    'enrollment'    => $enrollment,
                    'classroom'     => $classroom,
                    'annual_average'=> $annualAverage,
                    'decision'      => $decision,
                    'promotion'     => $promotion,
                    'term_count'    => $termReports->count(),
                ];
            }
        }

        return $results;
    }

    public function confirmPromotions(AcademicYear $year): int
    {
        $school     = Auth::user()->school;
        $admin      = Auth::user();
        $classrooms = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $year->id)
            ->with(['classLevel', 'stream'])
            ->get()
            ->keyBy('id');

        $promotions = Promotion::whereHas('enrollment', function($q) use ($year) {
            $q->where('academic_year_id', $year->id);
        })
        ->whereNull('confirmed_by')
        ->with(['enrollment.student', 'enrollment.classroom'])
        ->get();

        $nextYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', false)
            ->where('is_closed', false)
            ->where('start_date', '>', $year->end_date)
            ->orderBy('start_date')
            ->first();

        $processed = 0;

        foreach ($promotions as $promotion) {
            $enrollment = $promotion->enrollment;
            $classroom  = $classrooms->get($enrollment->classroom_id);

            if (!$classroom) continue;

            // Update enrollment status
            $statusMap = [
                'promoted'  => 'promoted',
                'repeated'  => 'repeated',
                'graduated' => 'graduated',
            ];

            $enrollment->update(['status' => $statusMap[$promotion->decision]]);

            // If next year exists, create new enrollment
            if ($nextYear && in_array($promotion->decision, ['promoted', 'repeated'])) {
                $nextClassLevel = $this->resolveNextClassLevel($classroom, $promotion->decision);

                if ($nextClassLevel) {
                    $nextClassroom = Classroom::where('school_id', $school->id)
                        ->where('academic_year_id', $nextYear->id)
                        ->where('class_level_id', $nextClassLevel->id)
                        ->where('stream_id', $classroom->stream_id)
                        ->first();

                    if ($nextClassroom) {
                        $nextEnrollment = StudentEnrollment::create([
                            'student_id'       => $enrollment->student_id,
                            'classroom_id'     => $nextClassroom->id,
                            'academic_year_id' => $nextYear->id,
                            'status'           => 'active',
                        ]);

                        $promotion->update(['next_enrollment_id' => $nextEnrollment->id]);
                    }
                }
            }

            $promotion->update([
                'confirmed_by' => $admin->id,
                'confirmed_at' => now(),
            ]);

            $processed++;
        }

        // Close the academic year
        $year->update(['is_closed' => true, 'is_current' => false]);

        // Set next year as current if exists
        if ($nextYear) {
            $nextYear->update(['is_current' => true]);
        }

        return $processed;
    }

    private function resolveNextClassLevel(Classroom $classroom, string $decision): ?ClassLevel
    {
        $currentLevel = $classroom->classLevel;

        if ($decision === 'repeated') {
            return $currentLevel;
        }

        // Promoted — move to next level
        return ClassLevel::where('order', $currentLevel->order + 1)->first();
    }
}