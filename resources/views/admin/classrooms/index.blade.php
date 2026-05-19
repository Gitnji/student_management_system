@extends('layouts.app')

@section('title', 'Classrooms — ICC SMS')
@section('header', 'Classrooms')
@section('subheader', 'Manage classrooms by academic year')
@section('breadcrumb', 'Classrooms')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <select id="year-filter"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal">
                <option value="">All Years</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('admin.classrooms.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Classroom
        </a>
    </div>

    @if($classrooms->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No classrooms yet. Create one to get started.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="classrooms-table">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Classroom</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Academic Year</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Form Master</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Students</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($classrooms as $classroom)
                        <tr class="hover:bg-gray-50 transition-colors" data-year="{{ $classroom->academic_year_id }}">
                            <td class="px-6 py-4 font-medium text-navy">{{ $classroom->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $classroom->academicYear->name }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $classroom->formMaster?->full_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $classroom->enrollments()->count() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.classrooms.edit', $classroom) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom) }}"
                                          onsubmit="return confirm('Delete this classroom?')">
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

@push('scripts')
<script>
    document.getElementById('year-filter').addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('#classrooms-table tbody tr').forEach(row => {
            row.style.display = (!selected || row.dataset.year === selected) ? '' : 'none';
        });
    });
</script>
@endpush