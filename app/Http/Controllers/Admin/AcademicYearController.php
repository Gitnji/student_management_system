<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicYearController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $academicYears = AcademicYear::where('school_id', $school->id)
            ->orderByDesc('start_date')
            ->get();

        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:20'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        $school = Auth::user()->school;

        // Only one current academic year allowed
        if ($request->boolean('is_current')) {
            AcademicYear::where('school_id', $school->id)
                ->update(['is_current' => false]);
            $data['is_current'] = true;
        }

        $data['school_id'] = $school->id;
        $data['is_closed']  = false;

        AcademicYear::create($data);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        $this->authorizeYear($academicYear);
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $this->authorizeYear($academicYear);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:20'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);

        $school = Auth::user()->school;

        if ($request->boolean('is_current')) {
            AcademicYear::where('school_id', $school->id)
                ->where('id', '!=', $academicYear->id)
                ->update(['is_current' => false]);
            $data['is_current'] = true;
        } else {
            $data['is_current'] = false;
        }

        $academicYear->update($data);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $this->authorizeYear($academicYear);

        if ($academicYear->is_closed) {
            return back()->with('error', 'Cannot delete a closed academic year.');
        }

        $academicYear->delete();

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Academic year deleted.');
    }

    private function authorizeYear(AcademicYear $academicYear): void
    {
        if ($academicYear->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}