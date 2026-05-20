@extends('public.layout')

@section('title', $page?->meta_title ?? 'Imperial Comprehensive College — Bamenda')
@section('meta_description', $page?->meta_description ?? '')

@section('content')

    {{-- Hero --}}
    <section class="bg-navy text-white py-24 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-sky-custom text-sm font-semibold uppercase tracking-widest mb-3">
                ICC NITOP III Mankon · Azire Old Church, Bamenda
            </p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                {{ $page?->content['hero']['heading'] ?? 'A Pinnacle of Excellence in Education' }}
            </h1>
            <p class="text-white/70 text-lg mb-8 max-w-2xl mx-auto">
                {{ $page?->content['hero']['subtext'] ?? '' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @if($page?->content['hero']['cta_text'] ?? null)
                    <a href="{{ $page->content['hero']['cta_link'] ?? '#' }}"
                       class="inline-block bg-royal hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                        {{ $page->content['hero']['cta_text'] }}
                    </a>
                @endif
                <a href="#contact"
                   class="inline-block border border-white/30 hover:border-white text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @if($page?->content['stats'] ?? null)
        <section class="bg-royal py-10 px-4">
            <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
                @foreach($page->content['stats'] as $stat)
                    <div>
                        <p class="text-3xl font-bold">{{ $stat['value'] }}</p>
                        <p class="text-white/70 text-sm mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- About --}}
    @if($page?->content['about']['text'] ?? null)
        <section class="py-16 px-4" id="about">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl font-bold text-navy mb-4">About ICC</h2>
                <div class="w-12 h-1 bg-royal mx-auto mb-6 rounded-full"></div>
                <p class="text-gray-600 leading-relaxed">{{ $page->content['about']['text'] }}</p>
            </div>
        </section>
    @endif

    {{-- Programs --}}
    @if($page?->content['programs'] ?? null)
        <section class="py-16 px-4 bg-light-gray" id="programs">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-navy mb-2">Programs Offered</h2>
                    <div class="w-12 h-1 bg-royal mx-auto rounded-full"></div>
                    <p class="text-gray-500 text-sm mt-3">Fees are moderate and paid in installments</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($page->content['programs'] as $program)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-royal transition-colors text-center">
                            <div class="w-12 h-12 rounded-xl bg-royal/10 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-navy mb-1">{{ $program['name'] }}</h3>
                            <p class="text-xs text-gray-400 mb-3">{{ $program['range'] }}</p>
                            <div class="bg-royal/10 rounded-lg px-3 py-1.5">
                                <span class="text-royal font-bold text-sm">{{ $program['fee'] }}</span>
                                <span class="text-gray-400 text-xs">/year</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Facilities --}}
    @if($page?->content['facilities'] ?? null)
        <section class="py-16 px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-navy mb-2">Our Facilities</h2>
                    <div class="w-12 h-1 bg-royal mx-auto rounded-full"></div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($page->content['facilities'] as $facility)
                        <div class="bg-light-gray rounded-xl p-4 text-center">
                            <div class="w-10 h-10 rounded-lg bg-navy flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-sky-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-navy">{{ $facility }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Latest News --}}
    @if($posts->isNotEmpty())
        <section class="py-16 px-4 bg-light-gray">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-navy">Latest News</h2>
                        <div class="w-12 h-1 bg-royal mt-2 rounded-full"></div>
                    </div>
                    <a href="{{ route('public.blog') }}" class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                        View all →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <a href="{{ route('public.blog.post', $post->slug) }}"
                           class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow block">
                            <p class="text-xs text-gray-400 mb-2">{{ $post->published_at->format('d M Y') }}</p>
                            <h3 class="font-semibold text-navy mb-2 line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-gray-500 text-sm line-clamp-3">
                                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            <span class="text-royal text-sm font-medium mt-3 inline-block">Read more →</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming Events --}}
    @if($events->isNotEmpty())
        <section class="py-16 px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-navy">Upcoming Events</h2>
                        <div class="w-12 h-1 bg-royal mt-2 rounded-full"></div>
                    </div>
                    <a href="{{ route('public.events') }}" class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                        View all →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($events as $event)
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-royal/10 flex items-center justify-center mb-3">
                                <span class="text-royal font-bold text-sm">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mb-1">{{ $event->start_date->format('M Y') }}</p>
                            <h3 class="font-semibold text-navy text-sm line-clamp-2">{{ $event->title }}</h3>
                            @if($event->location)
                                <p class="text-xs text-gray-400 mt-1">📍 {{ $event->location }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Contact --}}
    @if($page?->content['contact'] ?? null)
        <section class="py-16 px-4 bg-navy" id="contact">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl font-bold text-white mb-2">Get In Touch</h2>
                <div class="w-12 h-1 bg-royal mx-auto mb-4 rounded-full"></div>
                <p class="text-white/60 text-sm mb-3">
                    Registration for both old and new students is ongoing on campus during working hours, Tuesday to Friday.
                </p>
                <p class="text-white/60 text-sm mb-8">📍 {{ $page->content['contact']['address'] }}</p>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    @foreach($page->content['contact']['phones'] as $phone)
                        <a href="tel:{{ str_replace(' ', '', $phone) }}"
                           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4 text-sky-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $phone }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection