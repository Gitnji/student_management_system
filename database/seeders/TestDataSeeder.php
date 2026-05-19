<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassLevel;
use App\Models\Mark;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\SubjectCoefficient;
use App\Models\TeacherAssignment;
use App\Models\Term;
use App\Models\Sequence;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $school = \App\Models\School::first();

        // Academic Year
        $year = AcademicYear::create([
            'school_id'  => $school->id,
            'name'       => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date'   => '2026-06-30',
            'is_current' => true,
            'is_closed'  => false,
        ]);

        // Terms
        $term1 = Term::create([
            'academic_year_id' => $year->id,
            'term'             => 1,
            'start_date'       => '2025-09-01',
            'end_date'         => '2025-12-20',
            'next_term_begins' => '2026-01-08',
        ]);

        $term2 = Term::create([
            'academic_year_id' => $year->id,
            'term'             => 2,
            'start_date'       => '2026-01-08',
            'end_date'         => '2026-03-28',
            'next_term_begins' => '2026-04-14',
        ]);

        $term3 = Term::create([
            'academic_year_id' => $year->id,
            'term'             => 3,
            'start_date'       => '2026-04-14',
            'end_date'         => '2026-06-30',
            'next_term_begins' => null,
        ]);

        // Sequences
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term1->id, 'sequence_number' => 1, 'name' => 'Sequence 1', 'is_locked' => false]);
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term1->id, 'sequence_number' => 2, 'name' => 'Sequence 2', 'is_locked' => false]);
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term2->id, 'sequence_number' => 1, 'name' => 'Sequence 3', 'is_locked' => false]);
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term2->id, 'sequence_number' => 2, 'name' => 'Sequence 4', 'is_locked' => false]);
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term3->id, 'sequence_number' => 1, 'name' => 'Sequence 5', 'is_locked' => false]);
        Sequence::create(['academic_year_id' => $year->id, 'term_id' => $term3->id, 'sequence_number' => 2, 'name' => 'Sequence 6', 'is_locked' => false]);

        // Teacher
        $teacher = User::create([
            'school_id'            => $school->id,
            'first_name'           => 'John',
            'last_name'            => 'Doe',
            'email'                => 'teacher@icc.cm',
            'password'             => bcrypt('Teacher@123'),
            'role'                 => 'teacher',
            'must_change_password' => false,
            'is_active'            => true,
        ]);

        // Classroom — Form 3 Science
        $level    = ClassLevel::where('name', 'Form 3')->first();
        $stream   = Stream::where('name', 'Science')->first();
        $classroom = Classroom::create([
            'school_id'        => $school->id,
            'academic_year_id' => $year->id,
            'class_level_id'   => $level->id,
            'stream_id'        => $stream->id,
            'form_master_id'   => $teacher->id,
            'name'             => 'Form 3 Science',
        ]);

        // Subjects + Coefficients
        $subjects = [
            ['name' => 'Mathematics',     'code' => 'MATH', 'coef' => 5],
            ['name' => 'English Language','code' => 'ENG',  'coef' => 5],
            ['name' => 'French',          'code' => 'FR',   'coef' => 3],
            ['name' => 'Biology',         'code' => 'BIO',  'coef' => 3],
            ['name' => 'Chemistry',       'code' => 'CHEM', 'coef' => 3],
            ['name' => 'Physics',         'code' => 'PHY',  'coef' => 3],
            ['name' => 'Citizenship',     'code' => 'CIT',  'coef' => 2],
            ['name' => 'Computer Science','code' => 'CS',   'coef' => 2],
        ];

        $allLevels  = ClassLevel::all();
        $allStreams  = Stream::all();
        $subjectModels = [];

        foreach ($subjects as $s) {
            $subject = Subject::create([
                'school_id' => $school->id,
                'name'      => $s['name'],
                'code'      => $s['code'],
            ]);

            foreach ($allLevels as $lvl) {
                foreach ($allStreams as $str) {
                    SubjectCoefficient::create([
                        'subject_id'     => $subject->id,
                        'class_level_id' => $lvl->id,
                        'stream_id'      => $str->id,
                        'coefficient'    => $s['coef'],
                    ]);
                }
            }

            TeacherAssignment::create([
                'teacher_id'       => $teacher->id,
                'classroom_id'     => $classroom->id,
                'subject_id'       => $subject->id,
                'academic_year_id' => $year->id,
            ]);

            $subjectModels[] = ['model' => $subject, 'coef' => $s['coef']];
        }

        // Students + Enrollments + Marks
        $studentsData = [
            ['first_name' => 'Naomi',   'last_name' => 'Tebib',   'matricule' => 'ICC-2025-103', 'gender' => 'female'],
            ['first_name' => 'John',    'last_name' => 'Fon',     'matricule' => 'ICC-2025-104', 'gender' => 'male'],
            ['first_name' => 'Grace',   'last_name' => 'Mbah',    'matricule' => 'ICC-2025-105', 'gender' => 'female'],
            ['first_name' => 'Peter',   'last_name' => 'Ndi',     'matricule' => 'ICC-2025-106', 'gender' => 'male'],
            ['first_name' => 'Sandra',  'last_name' => 'Che',     'matricule' => 'ICC-2025-107', 'gender' => 'female'],
        ];

        $seq1 = Sequence::where('name', 'Sequence 1')->first();
        $seq2 = Sequence::where('name', 'Sequence 2')->first();

        // Sample marks matching the report card
        $marksData = [
            'ICC-2025-103' => [
                'MATH' => [14.50, 16.00],
                'ENG'  => [13.00, 15.50],
                'FR'   => [11.50, 13.00],
                'BIO'  => [16.00, 17.00],
                'CHEM' => [12.00, 13.50],
                'PHY'  => [9.50,  8.00],
                'CIT'  => [17.00, 18.00],
                'CS'   => [14.00, 15.00],
            ],
            'ICC-2025-104' => [
                'MATH' => [12.00, 13.00],
                'ENG'  => [11.00, 12.00],
                'FR'   => [10.00, 11.00],
                'BIO'  => [13.00, 14.00],
                'CHEM' => [11.00, 12.00],
                'PHY'  => [10.00, 11.00],
                'CIT'  => [14.00, 15.00],
                'CS'   => [12.00, 13.00],
            ],
            'ICC-2025-105' => [
                'MATH' => [15.00, 16.00],
                'ENG'  => [14.00, 15.00],
                'FR'   => [13.00, 14.00],
                'BIO'  => [15.00, 16.00],
                'CHEM' => [13.00, 14.00],
                'PHY'  => [12.00, 13.00],
                'CIT'  => [16.00, 17.00],
                'CS'   => [15.00, 16.00],
            ],
            'ICC-2025-106' => [
                'MATH' => [8.00,  9.00],
                'ENG'  => [9.00,  10.00],
                'FR'   => [7.00,  8.00],
                'BIO'  => [10.00, 11.00],
                'CHEM' => [8.00,  9.00],
                'PHY'  => [7.00,  8.00],
                'CIT'  => [11.00, 12.00],
                'CS'   => [9.00,  10.00],
            ],
            'ICC-2025-107' => [
                'MATH' => [17.00, 18.00],
                'ENG'  => [16.00, 17.00],
                'FR'   => [15.00, 16.00],
                'BIO'  => [17.00, 18.00],
                'CHEM' => [15.00, 16.00],
                'PHY'  => [14.00, 15.00],
                'CIT'  => [18.00, 19.00],
                'CS'   => [16.00, 17.00],
            ],
        ];

        $admin = User::where('role', 'admin')->first();

        foreach ($studentsData as $sd) {
            $student = Student::create([
                'school_id'     => $school->id,
                'first_name'    => $sd['first_name'],
                'last_name'     => $sd['last_name'],
                'matricule'     => $sd['matricule'],
                'date_of_birth' => '2010-09-01',
                'gender'        => $sd['gender'],
            ]);

            $enrollment = StudentEnrollment::create([
                'student_id'       => $student->id,
                'classroom_id'     => $classroom->id,
                'academic_year_id' => $year->id,
                'status'           => 'active',
            ]);

            $studentMarks = $marksData[$sd['matricule']] ?? [];

            foreach ($subjectModels as $sm) {
                $code = $sm['model']->code;
                if (isset($studentMarks[$code])) {
                    Mark::create([
                        'enrollment_id' => $enrollment->id,
                        'subject_id'    => $sm['model']->id,
                        'sequence_id'   => $seq1->id,
                        'created_by'    => $admin->id,
                        'mark'          => $studentMarks[$code][0],
                    ]);
                    Mark::create([
                        'enrollment_id' => $enrollment->id,
                        'subject_id'    => $sm['model']->id,
                        'sequence_id'   => $seq2->id,
                        'created_by'    => $admin->id,
                        'mark'          => $studentMarks[$code][1],
                    ]);
                }
            }
        }
    }
}