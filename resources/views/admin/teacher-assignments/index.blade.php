@extends('layouts.app')

@section('title', 'Teacher Assignments — ICC SMS')
@section('header', 'Teacher Assignments')
@section('subheader', 'Assign teachers to subjects and classrooms')
@section('breadcrumb', 'Assignments')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('admin.teacher-assignments.index') }}" class="flex items-center gap-3">
            <select name="year_id" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.teacher-assignments.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Assignment
        </a>
    </div>

    @if($assignments->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No assignments yet for this academic year.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Teacher</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Subject</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Classroom</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($assignments as $assignment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">
                                {{ $assignment->teacher->full_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $assignment->subject->name }}
                                @if($assignment->subject->code)
                                    <span class="text-gray-400 text-xs ml-1">({{ $assignment->subject->code }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $assignment->classroom->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end">
                                    <form method="POST" action="{{ route('admin.teacher-assignments.destroy', $assignment) }}"
                                          onsubmit="return confirm('Remove this assignment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection