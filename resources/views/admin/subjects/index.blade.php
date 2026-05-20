@extends('layouts.app')

@section('title', 'Subjects — ICC SMS')
@section('header', 'Subjects')
@section('subheader', 'Manage subjects and their coefficients')
@section('breadcrumb', 'Subjects')

@section('content')
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('import_errors'))
        <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg px-4 py-3 text-sm">
            <p class="font-medium mb-1">Skipped rows</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-end gap-3 mb-6">
        <form method="POST" action="{{ route('admin.subjects.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="import_file" accept=".csv,text/csv"
                   class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal" />
            <button type="submit"
                    class="bg-white border border-gray-200 hover:border-royal text-navy text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                Import CSV
            </button>
        </form>

        <a href="{{ route('admin.subjects.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Subject
        </a>
    </div>

    @if($subjects->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No subjects yet. Create one to get started.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Subject</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Code</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Assigned Teachers</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($subjects as $subject)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">{{ $subject->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $subject->code ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $subject->teacher_assignments_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.subjects.edit', $subject) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}"
                                          onsubmit="return confirm('Delete this subject?')">
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
