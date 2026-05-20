<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectCoefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $classLevels = $this->classLevels();
        $streams     = $this->streams();

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

    public function import(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'import_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $classLevels = $this->classLevels();
        $streams = $this->streams();
        $created = 0;
        $skipped = [];
        $seen = [];

        foreach ($this->csvRows($request->file('import_file')->getRealPath()) as $line => $row) {
            $row['code'] = strtoupper($row['code'] ?? '');
            $row['coefficient'] = $row['coefficient'] ?? 1;
            $key = strtolower(($row['code'] ?: $row['name'] ?? ''));

            $validator = Validator::make($row, [
                'name'        => ['required', 'string', 'max:100'],
                'code'        => ['nullable', 'string', 'max:10'],
                'coefficient' => ['required', 'integer', 'min:1', 'max:9'],
            ]);

            if ($key !== '' && isset($seen[$key])) {
                $validator->after(fn($validator) => $validator->errors()->add('name', 'Duplicate subject in import file.'));
            }

            $exists = Subject::where('school_id', $school->id)
                ->where(function ($query) use ($row) {
                    $query->where('name', $row['name'] ?? '');

                    if (!empty($row['code'])) {
                        $query->orWhere('code', $row['code']);
                    }
                })
                ->exists();

            if ($exists) {
                $validator->after(fn($validator) => $validator->errors()->add('name', 'Subject already exists.'));
            }

            if ($validator->fails()) {
                $skipped[] = "Row {$line}: " . $validator->errors()->first();
                continue;
            }

            $seen[$key] = true;

            $subject = Subject::create([
                'school_id' => $school->id,
                'name'      => $row['name'],
                'code'      => $row['code'] ?: null,
            ]);

            foreach ($classLevels as $classLevel) {
                foreach ($streams as $stream) {
                    SubjectCoefficient::create([
                        'subject_id'     => $subject->id,
                        'class_level_id' => $classLevel->id,
                        'stream_id'      => $stream->id,
                        'coefficient'    => (int) $row['coefficient'],
                    ]);
                }
            }

            $created++;
        }

        return redirect()->route('admin.subjects.index')
            ->with($created > 0 ? 'success' : 'error', "{$created} subject(s) imported. " . count($skipped) . ' row(s) skipped.')
            ->with('import_errors', $skipped);
    }

    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $classLevels  = $this->classLevels();
        $streams      = $this->streams();
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

    private function classLevels()
    {
        return ClassLevel::orderBy('order')->orderBy('name')->get();
    }

    private function streams()
    {
        Stream::firstOrCreate(['name' => 'General']);

        return Stream::orderBy('name')->get();
    }

    private function csvRows(string $path): array
    {
        $handle = fopen($path, 'r');
        $header = null;
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            if ($data === [null] || $data === false) {
                continue;
            }

            if ($header === null) {
                $header = array_map(fn($value) => strtolower(trim((string) $value)), $data);
                continue;
            }

            $data = array_pad($data, count($header), null);
            $rows[] = [count($rows) + 2, array_combine($header, array_map(fn($value) => trim((string) $value), $data))];
        }

        fclose($handle);

        return array_column($rows, 1, 0);
    }
}
