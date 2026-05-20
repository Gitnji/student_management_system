@extends('layouts.app')

@section('title', 'Teachers — ICC SMS')
@section('header', 'Teachers')
@section('subheader', 'Manage teacher accounts')
@section('breadcrumb', 'Teachers')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <input type="text" id="teacher-search" placeholder="Search teachers..."
               class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal w-56" />

        <a href="{{ route('admin.teachers.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Teacher
        </a>
    </div>

    @if($teachers->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No teachers yet. Add one to get started.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="teachers-table">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Name</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Email</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Password</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($teachers as $teacher)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-royal/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-royal text-xs font-bold">
                                            {{ strtoupper(substr($teacher->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-navy">{{ $teacher->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $teacher->email }}</td>
                            <td class="px-6 py-4">
                                @if($teacher->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($teacher->must_change_password)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Must change
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.teachers.toggle-active', $teacher) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="text-sm font-medium transition-colors {{ $teacher->is_active ? 'text-yellow-600 hover:text-yellow-700' : 'text-green-600 hover:text-green-700' }}">
                                            {{ $teacher->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}"
                                          onsubmit="return confirm('Delete this teacher? This cannot be undone.')">
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