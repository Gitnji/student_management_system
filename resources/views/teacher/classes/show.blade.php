@extends('layouts.app')

@section('title', $classroom->name . ' — ICC SMS')
@section('header', $classroom->name)
@section('subheader', 'Class overview and mark completion')
@section('breadcrumb', 'My Classes')

@section('content')

    <div class="flex justify-end mb-4">
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 hover:text-navy text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Class List
        </button>
    </div>

    {{-- Student List --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-navy mb-4">
                    Students ({{ $enrollments->count() }})
                </h3>
                @if($enrollments->isEmpty())
                    <p class="text-gray-400 text-sm">No students enrolled.</p>
                @else
                    <div class="space-y-2">
                        @foreach($enrollments as $i => $enrollment)
                            <div class="flex items-center gap-3 py-1.5">
                                <span class="text-xs text-gray-400 w-5">{{ $i + 1 }}</span>
                                <div class="w-7 h-7 rounded-full bg-royal/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-royal text-xs font-bold">
                                        {{ strtoupper(substr($enrollment->student->first_name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-navy">{{ $enrollment->student->full_name }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Mark Completion Matrix --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-navy mb-4">Mark Completion</h3>

                @if($sequences->isEmpty())
                    <p class="text-gray-400 text-sm">No sequences set up yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left text-xs font-semibold text-gray-500 pb-3 pr-4">Subject</th>
                                    @foreach($sequences as $sequence)
                                        <th class="text-center text-xs font-semibold text-gray-500 pb-3 px-2">
                                            {{ $sequence->name }}
                                            @if($sequence->is_locked)
                                                <span class="text-red-400">(locked)</span>
                                            @endif
                                        </th>
                                    @endforeach
                                    <th class="text-center text-xs font-semibold text-gray-500 pb-3 px-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td class="py-3 pr-4 font-medium text-navy">
                                            {{ $assignment->subject->name }}
                                        </td>
                                        @foreach($sequences as $sequence)
                                            @php
                                                $cell    = $markMatrix[$assignment->subject_id][$sequence->id] ?? null;
                                                $entered = $cell['entered'] ?? 0;
                                                $total   = $cell['total'] ?? 0;
                                                $complete = $total > 0 && $entered === $total;
                                                $partial  = $entered > 0 && $entered < $total;
                                            @endphp
                                            <td class="py-3 px-2 text-center">
                                                @if($total === 0)
                                                    <span class="text-gray-300 text-xs">—</span>
                                                @elseif($complete)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                        {{ $entered }}/{{ $total }}
                                                    </span>
                                                @elseif($partial)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                        {{ $entered }}/{{ $total }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                        0/{{ $total }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="py-3 px-2 text-center">
                                            <a href="{{ route('teacher.marks.enter', [
                                                    'classroom_id' => $classroom->id,
                                                    'subject_id'   => $assignment->subject_id,
                                                    'sequence_id'  => $sequences->where('is_locked', false)->first()?->id,
                                                ]) }}"
                                               class="text-xs text-royal hover:text-blue-700 font-medium transition-colors">
                                                Enter Marks
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection