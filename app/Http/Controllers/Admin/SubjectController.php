<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectCoefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SubjectController extends Controller
{
    public function index()
    {
        $school   = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)
            ->withCount('teacherAssignments')
            ->orderBy('name')
            ->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classLevels = Cache::get('class_levels');
        $streams     = Cache::get('streams');

        return view('admin.subjects.create', compact('classLevels', 'streams'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'name'                          => ['required', 'string', 'max:100'],
            'code'                          => ['nullable', 'string', 'max:10'],
            'coefficients'                  => ['required', 'array'],
            'coefficients.*.*'              => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $subject = Subject::create([
            'school_id' => $school->id,
            'name'      => $request->name,
            'code'      => $request->code,
        ]);

        // coefficients[class_level_id][stream_id] = value
        foreach ($request->coefficients as $classLevelId => $streams) {
            foreach ($streams as $streamId => $coefficient) {
                SubjectCoefficient::create([
                    'subject_id'     => $subject->id,
                    'class_level_id' => $classLevelId,
                    'stream_id'      => $streamId,
                    'coefficient'    => $coefficient,
                ]);
            }
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);

        $classLevels  = Cache::get('class_levels');
        $streams      = Cache::get('streams');
        $coefficients = SubjectCoefficient::where('subject_id', $subject->id)->get()
            ->groupBy('class_level_id')
            ->map(fn($rows) => $rows->keyBy('stream_id'));

        return view('admin.subjects.edit', compact('subject', 'classLevels', 'streams', 'coefficients'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);

        $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'code'             => ['nullable', 'string', 'max:10'],
            'coefficients'     => ['required', 'array'],
            'coefficients.*.*' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        // Delete and recreate coefficients
        SubjectCoefficient::where('subject_id', $subject->id)->delete();

        foreach ($request->coefficients as $classLevelId => $streams) {
            foreach ($streams as $streamId => $coefficient) {
                SubjectCoefficient::create([
                    'subject_id'     => $subject->id,
                    'class_level_id' => $classLevelId,
                    'stream_id'      => $streamId,
                    'coefficient'    => $coefficient,
                ]);
            }
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $this->authorizeSubject($subject);

        if ($subject->marks()->exists()) {
            return back()->with('error', 'Cannot delete a subject that has marks recorded.');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted.');
    }

    private function authorizeSubject(Subject $subject): void
    {
        if ($subject->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}