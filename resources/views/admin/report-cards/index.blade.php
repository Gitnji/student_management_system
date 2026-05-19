@extends('layouts.app')

@section('title', 'Report Cards — ICC SMS')
@section('header', 'Report Cards')
@section('subheader', 'Generate and view term report cards by classroom')
@section('breadcrumb', 'Report Cards')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('admin.report-cards.index') }}">
            <select name="year_id" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($classrooms->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No classrooms found for this academic year.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Classroom</th>
                        @foreach($terms as $term)
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">
                                {{ $term->term_name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($classrooms as $classroom)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">{{ $classroom->name }}</td>
                            @foreach($terms as $term)
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Generate button --}}
                                        <form method="POST" action="{{ route('admin.report-cards.generate') }}">
                                            @csrf
                                            <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                                            <input type="hidden" name="term_id" value="{{ $term->id }}">
                                            <button type="submit"
                                                    class="text-xs font-medium text-royal hover:text-blue-700 transition-colors">
                                                Generate
                                            </button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        {{-- View button --}}
                                        <a href="{{ route('admin.report-cards.show', ['classroom_id' => $classroom->id, 'term_id' => $term->id]) }}"
                                           class="text-xs font-medium text-gray-500 hover:text-navy transition-colors">
                                            View
                                        </a>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection