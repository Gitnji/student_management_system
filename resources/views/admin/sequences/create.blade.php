@extends('layouts.app')

@section('title', 'Add Term — ICC SMS')
@section('header', 'Add Term')
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

            <form method="POST" action="{{ route('admin.sequences.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Academic Year</label>
                    <select name="academic_year_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $currentYear?->id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Term</label>
                    <select name="term"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="">Select term</option>
                        <option value="1" {{ old('term') == 1 ? 'selected' : '' }}>First Term</option>
                        <option value="2" {{ old('term') == 2 ? 'selected' : '' }}>Second Term</option>
                        <option value="3" {{ old('term') == 3 ? 'selected' : '' }}>Third Term</option>
                    </select>
                    @error('term')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">
                        Next Term Begins <span class="text-gray-400 font-normal">(leave blank for Term 3)</span>
                    </label>
                    <input type="date" name="next_term_begins" value="{{ old('next_term_begins') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Create Term
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