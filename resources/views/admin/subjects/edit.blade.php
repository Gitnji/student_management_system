@extends('layouts.app')

@section('title', 'Edit Subject — ICC SMS')
@section('header', 'Edit Subject')
@section('breadcrumb', 'Subjects')

@section('content')
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

        <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Subject Name</label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Code <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="code" value="{{ old('code', $subject->code) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-navy mb-1">Coefficients per Class Level & Stream</h3>
                <p class="text-xs text-gray-400 mb-4">Set the coefficient (1–9) for each class level and stream combination.</p>

                <div class="overflow-x-auto">
                    <table class="text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-light-gray">
                                <th class="text-left text-xs font-semibold text-gray-500 px-4 py-3 border-b border-gray-200">Class Level</th>
                                @foreach($streams as $stream)
                                    <th class="text-center text-xs font-semibold text-gray-500 px-4 py-3 border-b border-gray-200 min-w-24">
                                        {{ $stream->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($classLevels as $level)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-navy">{{ $level->name }}</td>
                                    @foreach($streams as $stream)
                                        @php
                                            $existing = $coefficients->get($level->id)?->get($stream->id)?->coefficient ?? 1;
                                            $value = old("coefficients.{$level->id}.{$stream->id}", $existing);
                                        @endphp
                                        <td class="px-4 py-3 text-center">
                                            <input
                                                type="number"
                                                name="coefficients[{{ $level->id }}][{{ $stream->id }}]"
                                                value="{{ $value }}"
                                                min="1" max="9"
                                                class="w-16 text-center px-2 py-1.5 rounded-lg border border-gray-200 bg-white text-navy text-sm
                                                       focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent"
                                            />
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.subjects.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection