@extends('layouts.app')

@section('title', 'Dashboard — ICC SMS')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->full_name)
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Students</p>
            <p class="text-3xl font-bold text-navy mt-1">0</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Teachers</p>
            <p class="text-3xl font-bold text-navy mt-1">0</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Classrooms</p>
            <p class="text-3xl font-bold text-navy mt-1">0</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Active Subjects</p>
            <p class="text-3xl font-bold text-navy mt-1">0</p>
        </div>

    </div>
@endsection