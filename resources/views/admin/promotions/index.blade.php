@extends('layouts.app')

@section('title', 'Promotions — ICC SMS')
@section('header', 'Year-End Promotions')
@section('subheader', 'Compute and confirm student promotion decisions before closing the academic year')
@section('breadcrumb', 'Promotions')

@section('content')

    @if(!$currentYear)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg px-4 py-3 text-sm">
            No current academic year set.
        </div>
    @elseif($currentYear->is_closed)
        <div class="bg-gray-50 border border-gray-200 text-gray-600 rounded-lg px-4 py-3 text-sm">
            The current academic year is already closed.
        </div>
    @else
        {{-- Warning Banner --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
            <h3 class="text-yellow-800 font-semibold text-sm mb-1">⚠ This action is irreversible</h3>
            <p class="text-yellow-700 text-sm">
                Confirming promotions will close <strong>{{ $currentYear->name }}</strong>,
                update all student statuses, and create enrollments for the next academic year
                where classrooms exist. Proceed only when all term reports have been generated.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 mb-6">
            <form method="POST" action="{{ route('admin.promotions.compute') }}">
                @csrf
                <button type="submit"
                        class="bg-navy hover:bg-gray-800 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    {{ $computed ? 'Recompute Decisions' : 'Compute Promotion Decisions' }}
                </button>
            </form>

            @if($computed)
                <form method="POST" action="{{ route('admin.promotions.confirm') }}"
                      onsubmit="return confirm('Are you sure? This will close {{ $currentYear->name }} and cannot be undone.')">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        Confirm & Close Academic Year
                    </button>
                </form>
            @endif
        </div>

        @if($computed && $promotions->isNotEmpty())
            {{-- Summary Stats --}}
            @php
                $promoted  = $promotions->where('decision', 'promoted')->count();
                $repeated  = $promotions->where('decision', 'repeated')->count();
                $graduated = $promotions->where('decision', 'graduated')->count();
            @endphp
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $promoted }}</p>
                    <p class="text-sm text-gray-500 mt-1">Promoted</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $repeated }}</p>
                    <p class="text-sm text-gray-500 mt-1">Repeating</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $graduated }}</p>
                    <p class="text-sm text-gray-500 mt-1">Graduated</p>
                </div>
            </div>

            {{-- Decisions Table --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-light-gray">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Student</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Classroom</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Annual Average</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Decision</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Override</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($promotions as $promotion)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-navy">
                                    {{ $promotion->enrollment->student->full_name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $promotion->enrollment->classroom->name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold {{ $promotion->annual_average >= 10 ? 'text-green-700' : 'text-red-600' }}">
                                        {{ $promotion->annual_average }}/20
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $decisionColors = [
                                            'promoted'  => 'bg-green-100 text-green-700',
                                            'repeated'  => 'bg-yellow-100 text-yellow-700',
                                            'graduated' => 'bg-purple-100 text-purple-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $decisionColors[$promotion->decision] }}">
                                        {{ ucfirst($promotion->decision) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.promotions.update-decision', $promotion) }}"
                                          class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="decision"
                                                class="text-xs border border-gray-200 rounded px-2 py-1 text-navy focus:outline-none focus:ring-1 focus:ring-royal">
                                            <option value="promoted"  {{ $promotion->decision === 'promoted'  ? 'selected' : '' }}>Promoted</option>
                                            <option value="repeated"  {{ $promotion->decision === 'repeated'  ? 'selected' : '' }}>Repeated</option>
                                            <option value="graduated" {{ $promotion->decision === 'graduated' ? 'selected' : '' }}>Graduated</option>
                                        </select>
                                        <button type="submit"
                                                class="text-xs text-royal hover:text-blue-700 font-medium transition-colors">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endsection