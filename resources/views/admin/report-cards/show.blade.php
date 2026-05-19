@extends('layouts.app')

@section('title', 'Report Cards — ICC SMS')
@section('header', $classroom->name . ' · ' . $term->term_name)
@section('subheader', $term->academicYear->name)
@section('breadcrumb', 'Report Cards')

@section('content')
    @if($reports->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No reports generated yet for this classroom and term. Click Generate first.</p>
        </div>
    @else
        <div class="flex justify-end mb-6 gap-3">
            <a href="{{ route('admin.report-cards.index') }}"
               class="text-sm text-gray-500 hover:text-navy transition-colors">
                ← Back
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Pos</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Student</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Seq 1 Avg</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Seq 2 Avg</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Term Avg</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Conduct</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-navy">
                                {{ $report->position_in_class }}/{{ $report->class_size }}
                            </td>
                            <td class="px-6 py-4 font-medium text-navy">
                                {{ $report->enrollment->student->full_name }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $report->seq1_average }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $report->seq2_average }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $report->term_average >= 10 ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $report->term_average }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $conductColors = [
                                        'excellent' => 'bg-green-100 text-green-700',
                                        'good'      => 'bg-blue-100 text-blue-700',
                                        'average'   => 'bg-yellow-100 text-yellow-700',
                                        'poor'      => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $conductColors[$report->conduct_rating] ?? '' }}">
                                    {{ ucfirst($report->conduct_rating) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.report-cards.pdf', ['enrollment_id' => $report->enrollment_id, 'term_id' => $report->term_id]) }}"
                                    target="_blank"
                                    class="text-xs font-medium text-royal hover:text-blue-700 transition-colors">
                                    Download PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-100 bg-light-gray">
                @php $first = $reports->first(); @endphp
                <div class="flex items-center gap-8 text-sm">
                    <span><span class="text-gray-500">Class Size:</span> <span class="font-semibold text-navy">{{ $first->class_size }}</span></span>
                    <span><span class="text-gray-500">Class Average:</span> <span class="font-semibold text-navy">{{ $first->class_average }}/20</span></span>
                    <span><span class="text-gray-500">Highest:</span> <span class="font-semibold text-navy">{{ $first->highest_in_class }}/20</span></span>
                </div>
            </div>
        </div>
    @endif
@endsection