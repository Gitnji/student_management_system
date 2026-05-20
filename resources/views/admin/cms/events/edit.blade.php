@extends('layouts.app')

@section('title', 'Edit Event — ICC SMS')
@section('header', 'Edit Event')
@section('breadcrumb', 'Events')

@section('content')
    <div class="max-w-xl">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.cms.events.update', $event) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Start Date & Time</label>
                        <input type="datetime-local" name="start_date"
                               value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">End Date & Time</label>
                        <input type="datetime-local" name="end_date"
                               value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Location</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.cms.events.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection