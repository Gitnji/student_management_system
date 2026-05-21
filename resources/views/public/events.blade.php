@extends('public.layout')

@section('title', 'Events — Imperial Comprehensive College')

@section('content')

    {{-- Page Header --}}
    <section class="bg-navy text-white py-20 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter mb-4">
                Events
            </h1>
            <p class="text-white/70 text-lg max-w-xl mx-auto">
                Stay updated with all upcoming and past events at Imperial Comprehensive College
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-16">

        {{-- Upcoming Events --}}
        @if($upcomingEvents->isNotEmpty())
            <div class="mb-16">
                <div class="flex items-center gap-3 mb-8">
                    <h2 class="text-3xl font-bold text-navy">Upcoming Events</h2>
                    <div class="flex-1 h-px bg-gradient-to-r from-royal/40 to-transparent"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($upcomingEvents as $event)
                        <div class="group bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                            <div class="flex gap-6">
                                <!-- Date Box -->
                                <div class="w-20 h-20 flex-shrink-0 bg-royal rounded-2xl flex flex-col items-center justify-center text-white">
                                    <span class="text-3xl font-bold leading-none">{{ $event->start_date->format('d') }}</span>
                                    <span class="text-sm uppercase tracking-widest mt-1 opacity-90">{{ $event->start_date->format('M') }}</span>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-xl text-navy leading-tight mb-3 group-hover:text-royal transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                    
                                    <div class="space-y-2 text-sm">
                                        <p class="flex items-center gap-2 text-gray-500">
                                            <span class="text-royal">🗓️</span>
                                            <span>
                                                {{ $event->start_date->format('D, d M Y') }}
                                                @if($event->end_date)
                                                    — {{ $event->end_date->format('D, d M Y') }}
                                                @endif
                                            </span>
                                        </p>
                                        
                                        @if($event->start_date->format('H:i') !== '00:00')
                                            <p class="flex items-center gap-2 text-gray-500">
                                                <span class="text-royal">🕒</span>
                                                <span>
                                                    {{ $event->start_date->format('H:i') }}
                                                    @if($event->end_date) - {{ $event->end_date->format('H:i') }} @endif
                                                </span>
                                            </p>
                                        @endif

                                        @if($event->location)
                                            <p class="flex items-center gap-2 text-gray-500">
                                                <span class="text-royal">📍</span>
                                                <span>{{ $event->location }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    @if($event->description)
                                        <p class="mt-5 text-gray-600 text-sm line-clamp-3">
                                            {{ $event->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Past Events --}}
        @if($pastEvents->isNotEmpty())
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <h2 class="text-3xl font-bold text-navy">Past Events</h2>
                    <div class="flex-1 h-px bg-gradient-to-r from-gray-300 to-transparent"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pastEvents as $event)
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-block px-3 py-1 bg-light-gray text-navy text-xs font-medium rounded-full">
                                        {{ $event->start_date->format('d M Y') }}
                                    </span>
                                </div>
                                @if($event->location)
                                    <span class="text-xs text-gray-400">📍 {{ $event->location }}</span>
                                @endif
                            </div>
                            
                            <h3 class="font-semibold text-navy leading-tight mb-2">{{ $event->title }}</h3>
                            
                            @if($event->description)
                                <p class="text-sm text-gray-500 line-clamp-3">{{ $event->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty State --}}
        @if($upcomingEvents->isEmpty() && $pastEvents->isEmpty())
            <div class="text-center py-24">
                <div class="w-28 h-28 mx-auto bg-light-gray rounded-full flex items-center justify-center mb-8">
                    <span class="text-6xl">📅</span>
                </div>
                <h3 class="text-2xl font-semibold text-navy mb-3">No Events Yet</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    There are currently no scheduled events. Please check back later for updates.
                </p>
            </div>
        @endif

    </div>

@endsection