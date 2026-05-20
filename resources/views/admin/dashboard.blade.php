@extends('layouts.app')

@section('title', 'Dashboard — ICC SMS')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->full_name)
@section('breadcrumb', 'Dashboard')

@section('content')

    @if(!$currentYear)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg px-4 py-3 text-sm mb-6">
            No current academic year set.
            <a href="{{ route('admin.academic-years.index') }}" class="font-medium underline ml-1">Set one now →</a>
        </div>
    @else
        <div class="mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                Current Year: {{ $currentYear->name }}
            </span>
        </div>
    @endif

    {{-- Top Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Students</p>
            <p class="text-3xl font-bold text-navy mt-1">{{ $stats['students'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Active Teachers</p>
            <p class="text-3xl font-bold text-navy mt-1">{{ $stats['teachers'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Classrooms</p>
            <p class="text-3xl font-bold text-navy mt-1">{{ $stats['classrooms'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Subjects</p>
            <p class="text-3xl font-bold text-navy mt-1">{{ $stats['subjects'] }}</p>
        </div>
    </div>

    {{-- Class Performance --}}
    @if(!empty($classPerformance))
        <div class="mb-8">
            <h2 class="text-sm font-semibold text-navy mb-4">Class Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($classPerformance as $cp)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-navy text-sm">{{ $cp['classroom']->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $cp['student_count'] }} students</p>
                            </div>
                            @if($cp['has_reports'])
                                @php
                                    $avg = $cp['avg_performance'];
                                    $color = $avg >= 14 ? 'text-green-700 bg-green-100'
                                           : ($avg >= 10 ? 'text-yellow-700 bg-yellow-100'
                                           : 'text-red-700 bg-red-100');
                                @endphp
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $color }}">
                                    {{ $avg }}/20
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                    No reports
                                </span>
                            @endif
                        </div>

                        @if($cp['has_reports'])
                            <div class="space-y-2">
                                {{-- Pass rate bar --}}
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-gray-500">Pass Rate</span>
                                        <span class="font-medium text-navy">{{ $cp['pass_rate'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $cp['pass_rate'] >= 70 ? 'bg-green-500' : ($cp['pass_rate'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                             style="width: {{ $cp['pass_rate'] }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs pt-1">
                                    <span class="text-gray-500">Highest: <span class="font-medium text-navy">{{ $cp['highest'] }}/20</span></span>
                                    <span class="text-gray-500">Teachers: <span class="font-medium text-navy">{{ $cp['assigned_teachers'] }}</span></span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-400">Generate term reports to see performance data.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Sequence Progress --}}
    @if(!empty($sequenceProgress))
        <div class="mb-8">
            <h2 class="text-sm font-semibold text-navy mb-4">Sequence Mark Submission Progress</h2>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-light-gray">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Sequence</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Term</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Classes Submitted</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Progress</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($sequenceProgress as $sp)
                            @php
                                $pct = $sp['total_classrooms'] > 0
                                    ? round(($sp['classrooms_submitted'] / $sp['total_classrooms']) * 100)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-navy">{{ $sp['sequence']->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $sp['sequence']->term->term_name }}</td>
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $sp['classrooms_submitted'] }} / {{ $sp['total_classrooms'] }}
                                </td>
                                <td class="px-6 py-3 w-40">
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $pct === 100 ? 'bg-green-500' : 'bg-royal' }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if($sp['sequence']->is_locked)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Locked</span>
                                    @elseif($sp['complete'])
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Complete</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('admin.students.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-100 hover:border-royal hover:bg-blue-50 transition-colors text-center">
                <svg class="w-5 h-5 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-xs font-medium text-navy">Enroll Student</span>
            </a>
            <a href="{{ route('admin.teachers.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-100 hover:border-royal hover:bg-blue-50 transition-colors text-center">
                <svg class="w-5 h-5 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-xs font-medium text-navy">Add Teacher</span>
            </a>
            <a href="{{ route('admin.report-cards.index') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-100 hover:border-royal hover:bg-blue-50 transition-colors text-center">
                <svg class="w-5 h-5 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-xs font-medium text-navy">Report Cards</span>
            </a>
            <a href="{{ route('admin.promotions.index') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-100 hover:border-royal hover:bg-blue-50 transition-colors text-center">
                <svg class="w-5 h-5 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                <span class="text-xs font-medium text-navy">Promotions</span>
            </a>
        </div>
    </div>
@endsection