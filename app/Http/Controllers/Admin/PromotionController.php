<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index()
    {
        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        if (!$currentYear) {
            return view('admin.promotions.index', [
                'currentYear' => null,
                'results'     => [],
                'computed'    => false,
            ]);
        }

        $promotions = Promotion::whereHas('enrollment', fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->with(['enrollment.student', 'enrollment.classroom.classLevel', 'enrollment.classroom.stream'])
            ->get();

        return view('admin.promotions.index', [
            'currentYear' => $currentYear,
            'promotions'  => $promotions,
            'computed'    => $promotions->isNotEmpty(),
        ]);
    }

    public function compute(PromotionService $service)
    {
        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', true)
            ->firstOrFail();

        if ($currentYear->is_closed) {
            return back()->with('error', 'This academic year is already closed.');
        }

        try {
            $results = $service->computePromotions($currentYear);
            return redirect()->route('admin.promotions.index')
                ->with('success', count($results) . ' student promotion decisions computed. Review and confirm below.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateDecision(Request $request, Promotion $promotion)
    {
        $school = Auth::user()->school;

        if ($promotion->enrollment->classroom->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'decision' => ['required', 'in:promoted,repeated,graduated'],
        ]);

        $promotion->update(['decision' => $request->decision]);

        return back()->with('success', 'Decision updated.');
    }

    public function confirm(PromotionService $service)
    {
        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)
            ->where('is_current', true)
            ->firstOrFail();

        if ($currentYear->is_closed) {
            return back()->with('error', 'This academic year is already closed.');
        }

        try {
            $processed = $service->confirmPromotions($currentYear);
            return redirect()->route('admin.academic-years.index')
                ->with('success', "Academic year closed. {$processed} students processed and promoted.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}