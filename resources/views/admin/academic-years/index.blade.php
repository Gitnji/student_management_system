@extends('layouts.app')

@section('title', 'Academic Years — ICC SMS')
@section('header', 'Academic Years')
@section('subheader', 'Manage academic years for your school')
@section('breadcrumb', 'Academic Years')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.academic-years.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Academic Year
        </a>
    </div>

    @if($academicYears->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No academic years yet. Create one to get started.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Name</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Start Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">End Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($academicYears as $year)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">{{ $year->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $year->start_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $year->end_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($year->is_closed)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Closed
                                    </span>
                                @elseif($year->is_current)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Current
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    @if(!$year->is_closed)
                                        <a href="{{ route('admin.academic-years.edit', $year) }}"
                                           class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}"
                                              onsubmit="return confirm('Delete this academic year? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-sm">Locked</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection