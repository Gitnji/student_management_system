<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Sequence;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SequenceController extends Controller
{
    public function index(Request $request)
    {
        $school        = Auth::user()->school;
        $currentYear   = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $academicYears = AcademicYear::where('school_id', $school->id)->orderByDesc('start_date')->get();
        $yearId        = $request->get('year_id', $currentYear?->id);

        $terms = Term::where('academic_year_id', $yearId)
            ->with(['sequences' => fn($q) => $q->orderBy('sequence_number')])
            ->orderBy('term')
            ->get();

        return view('admin.sequences.index', compact('terms', 'academicYears', 'yearId', 'currentYear'));
    }

    public function create()
    {
        $school        = Auth::user()->school;
        $currentYear   = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $academicYears = AcademicYear::where('school_id', $school->id)->where('is_closed', false)->orderByDesc('start_date')->get();

        return view('admin.sequences.create', compact('academicYears', 'currentYear'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term'             => ['required', 'in:1,2,3'],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after:start_date'],
            'next_term_begins' => ['nullable', 'date'],
        ]);

        // Get or create the term
        $term = Term::firstOrCreate(
            [
                'academic_year_id' => $request->academic_year_id,
                'term'             => $request->term,
            ],
            [
                'start_date'       => $request->start_date,
                'end_date'         => $request->end_date,
                'next_term_begins' => $request->next_term_begins,
            ]
        );

        // Update term dates if already exists
        $term->update([
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'next_term_begins' => $request->next_term_begins,
        ]);

        // Create both sequences for the term if they don't exist
        $termNames = [1 => 'First', 2 => 'Second', 3 => 'Third'];
        $termLabel = $termNames[$request->term];

        foreach ([1, 2] as $seqNum) {
            Sequence::firstOrCreate(
                [
                    'term_id'         => $term->id,
                    'sequence_number' => $seqNum,
                ],
                [
                    'academic_year_id' => $request->academic_year_id,
                    'name'             => "Sequence " . (($request->term - 1) * 2 + $seqNum),
                    'is_locked'        => false,
                ]
            );
        }

        return redirect()->route('admin.sequences.index')
            ->with('success', "{$termLabel} Term and its sequences created successfully.");
    }

    public function edit(Term $term)
    {
        $school = Auth::user()->school;
        if ($term->academicYear->school_id !== $school->id) {
            abort(403);
        }

        $academicYears = AcademicYear::where('school_id', $school->id)->where('is_closed', false)->orderByDesc('start_date')->get();

        return view('admin.sequences.edit', compact('term', 'academicYears'));
    }

    public function update(Request $request, Term $term)
    {
        $school = Auth::user()->school;
        if ($term->academicYear->school_id !== $school->id) {
            abort(403);
        }

        $request->validate([
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after:start_date'],
            'next_term_begins' => ['nullable', 'date'],
        ]);

        $term->update([
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'next_term_begins' => $request->next_term_begins,
        ]);

        return redirect()->route('admin.sequences.index')
            ->with('success', 'Term updated successfully.');
    }

    public function toggleLock(Sequence $sequence)
    {
        $school = Auth::user()->school;
        if ($sequence->term->academicYear->school_id !== $school->id) {
            abort(403);
        }

        $sequence->update(['is_locked' => !$sequence->is_locked]);

        $status = $sequence->is_locked ? 'locked' : 'unlocked';

        return back()->with('success', "{$sequence->name} {$status} successfully.");
    }
}