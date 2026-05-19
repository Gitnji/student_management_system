@extends('layouts.app')

@section('title', 'Edit Term — ICC SMS')
@section('header', 'Edit Term Dates')
@section('breadcrumb', 'Sequences')

@section('content')
    <div class="max-w-xl">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-5 pb-5 border-b border-gray-100">
                <p class="text-sm text-gray-500">Editing dates for</p>
                <p class="text-navy font-semibold">{{ $term->term_name }} — {{ $term->academicYear->name }}</p>
            </div>

            <form method="POST" action="{{ route('admin.sequences.update', $term) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Start Date</label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', $term->start_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">End Date</label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', $term->end_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">
                        Next Term Begins <span class="text-gray-400 font-normal">(leave blank for Term 3)</span>
                    </label>
                    <input type="date" name="next_term_begins"
                           value="{{ old('next_term_begins', $term->next_term_begins?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.sequences.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection