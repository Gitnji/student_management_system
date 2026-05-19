@extends('layouts.app')

@section('title', 'Sequences — ICC SMS')
@section('header', 'Terms & Sequences')
@section('subheader', 'Manage academic terms and lock sequences when done')
@section('breadcrumb', 'Sequences')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('admin.sequences.index') }}" class="flex items-center gap-3">
            <select name="year_id" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-navy focus:outline-none focus:ring-2 focus:ring-royal">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.sequences.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Term
        </a>
    </div>

    @if($terms->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No terms set up for this academic year.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($terms as $term)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    {{-- Term Header --}}
                    <div class="flex items-center justify-between px-6 py-4 bg-navy">
                        <div>
                            <h3 class="text-white font-semibold">{{ $term->term_name }}</h3>
                            <p class="text-white/50 text-xs mt-0.5">
                                {{ $term->start_date->format('d M Y') }} →
                                {{ $term->end_date->format('d M Y') }}
                                @if($term->next_term_begins)
                                    · Next term: {{ $term->next_term_begins->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.sequences.edit', $term) }}"
                           class="text-white/60 hover:text-white text-sm transition-colors">
                            Edit Dates
                        </a>
                    </div>

                    {{-- Sequences --}}
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-light-gray">
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Sequence</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($term->sequences as $sequence)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-navy">
                                        {{ $sequence->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($sequence->is_locked)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Locked
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"/>
                                                </svg>
                                                Open
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <form method="POST" action="{{ route('admin.sequences.toggle-lock', $sequence) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-sm font-medium transition-colors {{ $sequence->is_locked ? 'text-green-600 hover:text-green-700' : 'text-red-500 hover:text-red-700' }}">
                                                    {{ $sequence->is_locked ? 'Unlock' : 'Lock' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif
@endsection