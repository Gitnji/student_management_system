<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ClassroomController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;

        $classrooms = Classroom::where('school_id', $school->id)
            ->with(['classLevel', 'stream', 'academicYear', 'formMaster'])
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('class_level_id')
            ->orderBy('stream_id')
            ->get();

        $academicYears = AcademicYear::where('school_id', $school->id)
            ->orderByDesc('start_date')
            ->get();

        return view('admin.classrooms.index', compact('classrooms', 'academicYears'));
    }

    public function create()
    {
        $school        = Auth::user()->school;
        $academicYears = AcademicYear::where('school_id', $school->id)->where('is_closed', false)->orderByDesc('start_date')->get();
        $classLevels   = Cache::get('class_levels');
        $streams       = Cache::get('streams');
        $teachers      = User::where('school_id', $school->id)->where('role', 'teacher')->where('is_active', true)->orderBy('first_name')->get();

        return view('admin.classrooms.create', compact('academicYears', 'classLevels', 'streams', 'teachers'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_level_id'   => ['required', 'exists:class_levels,id'],
            'stream_id'        => ['required', 'exists:streams,id'],
            'form_master_id'   => ['nullable', 'exists:users,id'],
        ]);

        // Auto-generate name
        $level  = ClassLevel::find($data['class_level_id']);
        $stream = Stream::find($data['stream_id']);
        $data['name']      = $level->name . ' ' . $stream->name;
        $data['school_id'] = $school->id;

        // Prevent duplicate classroom in same year
        $exists = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $data['academic_year_id'])
            ->where('class_level_id', $data['class_level_id'])
            ->where('stream_id', $data['stream_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['class_level_id' => 'This classroom already exists for the selected academic year.']);
        }

        Classroom::create($data);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        $this->authorizeClassroom($classroom);

        $school        = Auth::user()->school;
        $academicYears = AcademicYear::where('school_id', $school->id)->where('is_closed', false)->orderByDesc('start_date')->get();
        $classLevels   = Cache::get('class_levels');
        $streams       = Cache::get('streams');
        $teachers      = User::where('school_id', $school->id)->where('role', 'teacher')->where('is_active', true)->orderBy('first_name')->get();

        return view('admin.classrooms.edit', compact('classroom', 'academicYears', 'classLevels', 'streams', 'teachers'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $this->authorizeClassroom($classroom);

        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_level_id'   => ['required', 'exists:class_levels,id'],
            'stream_id'        => ['required', 'exists:streams,id'],
            'form_master_id'   => ['nullable', 'exists:users,id'],
        ]);

        $level  = ClassLevel::find($data['class_level_id']);
        $stream = Stream::find($data['stream_id']);
        $data['name'] = $level->name . ' ' . $stream->name;

        $classroom->update($data);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $this->authorizeClassroom($classroom);

        if ($classroom->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete a classroom that has enrolled students.');
        }

        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom deleted.');
    }

    private function authorizeClassroom(Classroom $classroom): void
    {
        if ($classroom->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}