@extends('layouts.app')

@section('title', 'Edit Classroom — ICC SMS')
@section('header', 'Edit Classroom')
@section('breadcrumb', 'Classrooms')

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

            <form method="POST" action="{{ route('admin.classrooms.update', $classroom) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Academic Year</label>
                    <select name="academic_year_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $classroom->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Class Level</label>
                    <select name="class_level_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        @foreach($classLevels as $level)
                            <option value="{{ $level->id }}" {{ old('class_level_id', $classroom->class_level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Stream</label>
                    <select name="stream_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        @foreach($streams as $stream)
                            <option value="{{ $stream->id }}" {{ old('stream_id', $classroom->stream_id) == $stream->id ? 'selected' : '' }}>
                                {{ $stream->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Form Master <span class="text-gray-400 font-normal">(optional)</span></label>
                    <select name="form_master_id"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="">No form master assigned</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('form_master_id', $classroom->form_master_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.classrooms.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection