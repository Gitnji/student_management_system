<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $school       = Auth::user()->school;
        $currentYear  = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $academicYears = AcademicYear::where('school_id', $school->id)->orderByDesc('start_date')->get();

        $yearId = $request->get('year_id', $currentYear?->id);

        $enrollments = StudentEnrollment::with(['student', 'classroom.classLevel', 'classroom.stream'])
            ->whereHas('classroom', fn($q) => $q->where('school_id', $school->id))
            ->where('academic_year_id', $yearId)
            ->orderBy('classroom_id')
            ->get();

        return view('admin.students.index', compact('enrollments', 'academicYears', 'yearId', 'currentYear'));
    }

    public function create()
    {
        $school       = Auth::user()->school;
        $currentYear  = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $classrooms   = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $currentYear?->id)
            ->with(['classLevel', 'stream'])
            ->orderBy('name')
            ->get();

        return view('admin.students.create', compact('classrooms', 'currentYear'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'matricule'     => ['required', 'string', 'max:30', 'unique:students,matricule'],
            'date_of_birth' => ['nullable', 'date'],
            'gender'        => ['required', 'in:male,female'],
            'classroom_id'  => ['required', 'exists:classrooms,id'],
        ]);

        $classroom = Classroom::findOrFail($data['classroom_id']);

        // Ensure classroom belongs to this school
        if ($classroom->school_id !== $school->id) {
            abort(403);
        }

        $student = Student::create([
            'school_id'     => $school->id,
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'matricule'     => $data['matricule'],
            'date_of_birth' => $data['date_of_birth'],
            'gender'        => $data['gender'],
        ]);

        StudentEnrollment::create([
            'student_id'       => $student->id,
            'classroom_id'     => $classroom->id,
            'academic_year_id' => $classroom->academic_year_id,
            'status'           => 'active',
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student enrolled successfully.');
    }

    public function import(Request $request)
    {
        $school = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();

        if (!$currentYear) {
            return back()->with('error', 'Set a current academic year before importing students.');
        }

        $request->validate([
            'import_file' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
        ]);

        $classrooms = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $currentYear->id)
            ->get()
            ->keyBy(fn($classroom) => strtolower($classroom->name));

        $created = 0;
        $skipped = [];
        $seenMatricules = [];

        foreach ($this->csvRows($request->file('import_file')->getRealPath()) as $line => $row) {
            $row['gender'] = strtolower($row['gender'] ?? '');
            $row['matricule'] = strtoupper($row['matricule'] ?? '');
            $row['date_of_birth'] = ($row['date_of_birth'] ?? '') === '' ? null : $row['date_of_birth'];

            $validator = Validator::make($row, [
                'first_name'    => ['required', 'string', 'max:100'],
                'last_name'     => ['required', 'string', 'max:100'],
                'matricule'     => ['required', 'string', 'max:30', 'unique:students,matricule'],
                'gender'        => ['required', 'in:male,female'],
                'date_of_birth' => ['nullable', 'date'],
                'classroom'     => ['required', 'string'],
            ]);

            if (isset($seenMatricules[$row['matricule']])) {
                $validator->after(fn($validator) => $validator->errors()->add('matricule', 'Duplicate matricule in import file.'));
            }

            $classroom = $this->resolveClassroom($row['classroom'] ?? '', $classrooms);
            if (!$classroom) {
                $validator->after(fn($validator) => $validator->errors()->add('classroom', 'Classroom was not found for the current academic year.'));
            }

            if ($validator->fails()) {
                $skipped[] = "Row {$line}: " . $validator->errors()->first();
                continue;
            }

            $seenMatricules[$row['matricule']] = true;

            $student = Student::create([
                'school_id'     => $school->id,
                'first_name'    => $row['first_name'],
                'last_name'     => $row['last_name'],
                'matricule'     => $row['matricule'],
                'date_of_birth' => $row['date_of_birth'] ?: null,
                'gender'        => $row['gender'],
            ]);

            StudentEnrollment::create([
                'student_id'       => $student->id,
                'classroom_id'     => $classroom->id,
                'academic_year_id' => $currentYear->id,
                'status'           => 'active',
            ]);

            $created++;
        }

        return redirect()->route('admin.students.index')
            ->with($created > 0 ? 'success' : 'error', "{$created} student(s) imported. " . count($skipped) . ' row(s) skipped.')
            ->with('import_errors', $skipped);
    }

    public function edit(Student $student)
    {
        $this->authorizeStudent($student);

        $school      = Auth::user()->school;
        $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();
        $classrooms  = Classroom::where('school_id', $school->id)
            ->where('academic_year_id', $currentYear?->id)
            ->with(['classLevel', 'stream'])
            ->orderBy('name')
            ->get();

        $currentEnrollment = $student->enrollments()
            ->where('academic_year_id', $currentYear?->id)
            ->first();

        return view('admin.students.edit', compact('student', 'classrooms', 'currentEnrollment', 'currentYear'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeStudent($student);

        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'matricule'     => ['required', 'string', 'max:30', 'unique:students,matricule,' . $student->id],
            'date_of_birth' => ['nullable', 'date'],
            'gender'        => ['required', 'in:male,female'],
            'classroom_id'  => ['nullable', 'exists:classrooms,id'],
        ]);

        $student->update([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'matricule'     => $data['matricule'],
            'date_of_birth' => $data['date_of_birth'],
            'gender'        => $data['gender'],
        ]);

        // Update classroom if changed
        if (!empty($data['classroom_id'])) {
            $school      = Auth::user()->school;
            $currentYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first();

            $enrollment = $student->enrollments()
                ->where('academic_year_id', $currentYear?->id)
                ->first();

            if ($enrollment) {
                $enrollment->update(['classroom_id' => $data['classroom_id']]);
            }
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeStudent($student);

        if ($student->enrollments()->whereHas('marks')->exists()) {
            return back()->with('error', 'Cannot delete a student with recorded marks.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted.');
    }

    private function authorizeStudent(Student $student): void
    {
        if ($student->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }

    private function resolveClassroom(string $value, $classrooms): ?Classroom
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            return $classrooms->firstWhere('id', (int) $value);
        }

        return $classrooms->get(strtolower($value));
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
