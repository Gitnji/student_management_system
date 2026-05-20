@extends('layouts.app')

@section('title', 'My Classes — ICC SMS')
@section('header', 'My Classes')
@section('subheader', 'Your assigned classrooms for the current academic year')
@section('breadcrumb', 'My Classes')

@section('content')
    @if(!$currentYear)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg px-4 py-3 text-sm">
            No current academic year set.
        </div>
    @elseif($assignments->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">You have no class assignments for {{ $currentYear->name }}.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($assignments as $classroomId => $classAssignments)
                @php $classroom = $classAssignments->first()->classroom; @endphp
                <a href="{{ route('teacher.classes.show', $classroomId) }}"
                   class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-royal transition-all block">

                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-royal/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">{{ $classAssignments->count() }} subject(s)</span>
                    </div>

                    <h3 class="font-semibold text-navy mb-1">{{ $classroom->name }}</h3>
                    <p class="text-xs text-gray-400 mb-3">{{ $currentYear->name }}</p>

                    <div class="flex flex-wrap gap-1">
                        @foreach($classAssignments as $assignment)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-light-gray text-gray-600">
                                {{ $assignment->subject->code ?? $assignment->subject->name }}
                            </span>
                        @endforeach
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection