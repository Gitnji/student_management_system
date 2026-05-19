@extends('layouts.app')

@section('title', 'Enter Marks — ICC SMS')
@section('header', 'Enter Marks')
@section('subheader', $classroom->name . ' · ' . $subject->name . ' · ' . $sequence->name)
@section('breadcrumb', 'Marks')

@section('content')
    @if($sequence->is_locked)
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-6">
            This sequence is locked. Marks cannot be edited.
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-light-gray flex items-center justify-between">
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span><span class="font-medium text-navy">Class:</span> {{ $classroom->name }}</span>
                <span><span class="font-medium text-navy">Subject:</span> {{ $subject->name }}</span>
                <span><span class="font-medium text-navy">Sequence:</span> {{ $sequence->name }}</span>
            </div>
            <a href="{{ route('teacher.marks.index') }}"
               class="text-sm text-gray-500 hover:text-navy transition-colors">
                ← Back
            </a>
        </div>

        <form method="POST" action="{{ route('teacher.marks.save') }}">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $assignment->classroom_id }}">
            <input type="hidden" name="subject_id"   value="{{ $assignment->subject_id }}">
            <input type="hidden" name="sequence_id"  value="{{ $sequence->id }}">

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">#</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Student</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Matricule</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Mark /20</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Remark</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($enrollments as $i => $enrollment)
                        @php
                            $existing = $existingMarks[$enrollment->id] ?? null;
                            $mark     = $existing?->mark;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-6 py-3 font-medium text-navy">
                                {{ $enrollment->student->full_name }}
                            </td>
                            <td class="px-6 py-3 text-gray-500 font-mono text-xs">
                                {{ $enrollment->student->matricule }}
                            </td>
                            <td class="px-6 py-3">
                                @if($sequence->is_locked)
                                    <span class="text-navy font-medium">{{ $mark ?? '—' }}</span>
                                @else
                                    <input
                                        type="number"
                                        name="marks[{{ $enrollment->id }}]"
                                        value="{{ $mark }}"
                                        min="0" max="20" step="0.25"
                                        class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-navy text-sm text-center
                                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent
                                               @error('marks.' . $enrollment->id) border-red-300 @enderror"
                                        placeholder="0–20"
                                    />
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if($mark !== null)
                                    @php
                                        [$color, $letter] = match(true) {
                                            $mark >= 18 => ['text-green-700 bg-green-100',  'A'],
                                            $mark >= 15 => ['text-blue-700 bg-blue-100',    'B'],
                                            $mark >= 12 => ['text-yellow-700 bg-yellow-100','C'],
                                            $mark >= 10 => ['text-orange-700 bg-orange-100','D'],
                                            default     => ['text-red-700 bg-red-100',      'E'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $color }}">
                                        {{ $letter }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(!$sequence->is_locked)
                <div class="px-6 py-4 border-t border-gray-100 bg-light-gray flex items-center gap-3">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Save All Marks
                    </button>
                    <a href="{{ route('teacher.marks.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <span class="text-xs text-gray-400 ml-auto">
                        {{ $enrollments->count() }} students · marks out of 20 · step 0.25
                    </span>
                </div>
            @endif
        </form>
    </div>
@endsection