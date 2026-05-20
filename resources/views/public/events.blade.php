@extends('public.layout')

@section('title', 'Events — ICC')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-navy mb-10">Events</h1>

        @if($upcomingEvents->isNotEmpty())
            <h2 class="text-lg font-semibold text-navy mb-4">Upcoming</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                @foreach($upcomingEvents as $event)
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-l-4 border-royal">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-royal flex flex-col items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-lg leading-none">{{ $event->start_date->format('d') }}</span>
                                <span class="text-white/70 text-xs">{{ $event->start_date->format('M') }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-navy mb-1">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-400 mb-2">
                                    {{ $event->start_date->format('d M Y, H:i') }}
                                    @if($event->end_date) — {{ $event->end_date->format('H:i') }} @endif
                                </p>
                                @if($event->location)
                                    <p class="text-xs text-gray-500">📍 {{ $event->location }}</p>
                                @endif
                                @if($event->description)
                                    <p class="text-sm text-gray-600 mt-2">{{ $event->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($pastEvents->isNotEmpty())
            <h2 class="text-lg font-semibold text-gray-400 mb-4">Past Events</h2>
            <div class="space-y-2">
                @foreach($pastEvents as $event)
                    <div class="bg-gray-50 rounded-lg px-5 py-3 flex items-center justify-between opacity-70">
                        <span class="text-sm text-navy font-medium">{{ $event->title }}</span>
                        <span class="text-xs text-gray-400">{{ $event->start_date->format('d M Y') }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($upcomingEvents->isEmpty() && $pastEvents->isEmpty())
            <p class="text-gray-400">No events yet.</p>
        @endif
    </div>
@endsection