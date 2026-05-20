@extends('layouts.app')

@section('title', 'Students — ICC SMS')
@section('header', 'Students')
@section('subheader', 'Manage student enrollments')
@section('breadcrumb', 'Students')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-3">
            <select name="year_id" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                @endforeach
            </select>

            <input type="text" id="student-search" placeholder="Search students..."
                   class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal w-56" />
        </form>

        <a href="{{ route('admin.students.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Enroll Student
        </a>
    </div>

    @if($enrollments->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No students enrolled for this academic year.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="students-table">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Student</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Matricule</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Classroom</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Gender</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">
                                {{ $enrollment->student->full_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                                {{ $enrollment->student->matricule }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $enrollment->classroom->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 capitalize">
                                {{ $enrollment->student->gender }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active'    => 'bg-green-100 text-green-700',
                                        'promoted'  => 'bg-blue-100 text-blue-700',
                                        'repeated'  => 'bg-yellow-100 text-yellow-700',
                                        'graduated' => 'bg-purple-100 text-purple-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$enrollment->status] ?? '' }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.students.edit', $enrollment->student) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $enrollment->student) }}"
                                          onsubmit="return confirm('Delete this student? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                                            Delete
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