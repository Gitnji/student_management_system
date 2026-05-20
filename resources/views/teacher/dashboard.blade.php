@extends('layouts.app')

@section('title', 'Dashboard — ICC SMS')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->full_name)
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">My Classes</p>
            <p class="text-3xl font-bold text-navy mt-1">{{ $assignedClasses }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Active Sequence</p>
            <p class="text-xl font-bold text-navy mt-1">{{ $activeSequence?->name ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Academic Year</p>
            <p class="text-xl font-bold text-navy mt-1">{{ $currentYear?->name ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Quick Actions</h3>
        <div class="flex gap-3">
            <a href="{{ route('teacher.marks.index') }}"
               class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Enter Marks
            </a>
        </div>
    </div>
@endsection