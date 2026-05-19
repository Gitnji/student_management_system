@extends('layouts.app')

@section('title', 'Enter Marks — ICC SMS')
@section('header', 'Enter Marks')
@section('subheader', 'Select a class, subject and sequence to enter marks')
@section('breadcrumb', 'Marks')

@section('content')
    @if(!$currentYear)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg px-4 py-3 text-sm">
            No current academic year set. Contact the administrator.
        </div>
    @elseif($assignments->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">You have no class assignments for the current academic year.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
            <h3 class="text-sm font-semibold text-navy mb-4">Select class, subject and sequence</h3>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('teacher.marks.enter') }}" class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Classroom</label>
                    <select name="classroom_id" id="classroom_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="">Select classroom</option>
                        @foreach($assignments as $classroomId => $classAssignments)
                            <option value="{{ $classroomId }}">
                                {{ $classAssignments->first()->classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Subject</label>
                    <select name="subject_id" id="subject_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="">Select classroom first</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Sequence</label>
                    <select name="sequence_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="">Select sequence</option>
                        @foreach($sequences as $sequence)
                            <option value="{{ $sequence->id }}"
                                    {{ $sequence->is_locked ? 'disabled' : '' }}>
                                {{ $sequence->name }}
                                {{ $sequence->is_locked ? '(Locked)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                    Load Students
                </button>
            </form>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    const assignments = {{ Js::from($assignmentsJson) }};

    document.getElementById('classroom_id').addEventListener('change', function () {
        const classroomId = this.value;
        const subjectSelect = document.getElementById('subject_id');
        subjectSelect.innerHTML = '<option value="">Select subject</option>';

        if (classroomId && assignments[classroomId]) {
            assignments[classroomId].forEach(function(subject) {
                const option = document.createElement('option');
                option.value = subject.subject_id;
                option.textContent = subject.subject_name + (subject.subject_code ? ' (' + subject.subject_code + ')' : '');
                subjectSelect.appendChild(option);
            });
        }
    });
</script>
@endpush