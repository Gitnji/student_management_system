@extends('public.layout')

@section('title', $page?->meta_title ?? 'Imperial Comprehensive College — Bamenda')
@section('meta_description', $page?->meta_description ?? '')

@section('content')

    {{-- Hero --}}
    <section class="relative bg-navy text-white overflow-hidden">
        <!-- Background accent -->
        <div class="absolute inset-0 bg-gradient-to-br from-royal/20 via-transparent to-sky-custom/10"></div>
        
        <div class="max-w-6xl mx-auto px-6 py-28 md:py-40 relative z-10 text-center">
            <p class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-5 py-2 rounded-3xl text-sky-custom text-sm font-semibold uppercase tracking-widest mb-6">
                ICC NITOP III • Mankon · Azire Old Church, Bamenda
            </p>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight tracking-tighter mb-6">
                {{ $page?->content['hero']['heading'] ?? 'A Pinnacle of Excellence in Education' }}
            </h1>
            
            <p class="text-xl text-white/80 max-w-2xl mx-auto mb-10">
                {{ $page?->content['hero']['subtext'] ?? 'Shaping future leaders through quality education, innovation, and character development.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if($page?->content['hero']['cta_text'] ?? null)
                    <a href="{{ $page->content['hero']['cta_link'] ?? '#' }}"
                       class="group inline-flex items-center justify-center bg-royal hover:bg-blue-600 text-white font-semibold px-10 py-4 rounded-2xl transition-all duration-300 hover:scale-105 shadow-lg shadow-royal/30">
                        {{ $page->content['hero']['cta_text'] }}
                        <span class="ml-2 transition-transform group-hover:translate-x-1">→</span>
                    </a>
                @endif
                
                <a href="#contact"
                   class="inline-flex items-center justify-center border border-white/40 hover:border-white bg-white/10 hover:bg-white/15 backdrop-blur-md text-white font-semibold px-10 py-4 rounded-2xl transition-all duration-300">
                    Contact Us
                </a>
            </div>
        </div>

        <!-- Decorative bottom curve -->
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-light-gray to-transparent"></div>
    </section>

    {{-- Stats --}}
@if($page?->content['stats'] ?? null)
    <section class="bg-royal py-16 -mt-8 relative z-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($page->content['stats'] as $stat)
                    <div class="group bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 text-center hover:bg-white/15 transition-all duration-300 hover:-translate-y-1">
                        <p class="text-5xl md:text-6xl font-bold tracking-tighter text-white transition-all group-hover:scale-110 duration-300">
                            {{ $stat['value'] }}
                        </p>
                        <p class="text-white/80 text-sm mt-3 font-medium tracking-wide">
                            {{ $stat['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

    {{-- About --}}
    @if($page?->content['about']['text'] ?? null)
        <section class="py-20 px-6 bg-white" id="about">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 text-royal mb-4">
                    <div class="w-8 h-px bg-royal"></div>
                    <span class="uppercase text-sm font-semibold tracking-widest">Discover ICC</span>
                    <div class="w-8 h-px bg-royal"></div>
                </div>
                
                <h2 class="text-4xl font-bold text-navy mb-6">About Imperial Comprehensive College</h2>
                
                <p class="text-lg text-gray-600 leading-relaxed">
                    {{ $page->content['about']['text'] }}
                </p>
            </div>
        </section>
    @endif

    {{-- Programs --}}
    @if($page?->content['programs'] ?? null)
        <section class="py-20 px-6 bg-light-gray" id="programs">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-navy">Programs Offered</h2>
                    <p class="text-gray-500 mt-3">Moderate fees • Flexible installment payment</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($page->content['programs'] as $program)
                        <div class="group bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-transparent hover:border-royal/20">
                            <div class="w-14 h-14 rounded-2xl bg-royal/10 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            
                            <h3 class="font-bold text-xl text-navy mb-1 text-center">{{ $program['name'] }}</h3>
                            <p class="text-center text-gray-400 text-sm mb-6">{{ $program['range'] }}</p>
                            
                            <div class="bg-gradient-to-r from-royal/5 to-sky-custom/5 rounded-2xl py-4 text-center">
                                <span class="text-3xl font-bold text-royal">{{ $program['fee'] }}</span>
                                <span class="text-gray-500 text-sm"> / year</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Facilities --}}
    @if($page?->content['facilities'] ?? null)
        <section class="py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-navy">Our Facilities</h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($page->content['facilities'] as $facility)
                        <div class="bg-white border border-gray-100 hover:border-royal/30 rounded-3xl p-8 text-center transition-all hover:-translate-y-1">
                            <div class="w-12 h-12 mx-auto mb-5 bg-navy rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="font-medium text-navy">{{ $facility }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Latest News --}}
    @if($posts->isNotEmpty())
        <section class="py-20 px-6 bg-light-gray">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-4xl font-bold text-navy">Latest News</h2>
                    </div>
                    <a href="{{ route('public.blog') }}" class="text-royal hover:text-blue-600 font-medium flex items-center gap-2 group">
                        View all 
                        <span class="transition-transform group-hover:translate-x-1">→</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <a href="{{ route('public.blog.post', $post->slug) }}" 
                           class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all">
                            <div class="p-8">
                                <p class="text-xs text-gray-400 mb-3">{{ $post->published_at->format('d M Y') }}</p>
                                <h3 class="font-semibold text-xl text-navy line-clamp-3 mb-4 group-hover:text-royal transition-colors">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-gray-600 line-clamp-4 text-sm">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 140) }}
                                </p>
                            </div>
                            <div class="border-t border-gray-100 px-8 py-5 text-royal text-sm font-medium flex items-center justify-between">
                                Read more
                                <span class="text-xl leading-none transition-transform group-hover:translate-x-1">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming Events --}}
    @if($events->isNotEmpty())
        <section class="py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-end mb-10">
                    <h2 class="text-4xl font-bold text-navy">Upcoming Events</h2>
                    <a href="{{ route('public.events') }}" class="text-royal hover:text-blue-600 font-medium flex items-center gap-2">
                        View all →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-3xl p-7 border border-gray-100 hover:border-royal/30 transition-all hover:shadow-lg">
                            <div class="w-16 h-16 rounded-2xl bg-royal/10 flex items-center justify-center mb-6">
                                <span class="text-4xl font-bold text-royal">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <p class="uppercase text-xs tracking-widest text-gray-400 mb-1">{{ $event->start_date->format('M Y') }}</p>
                            <h3 class="font-semibold text-navy leading-tight mb-3">{{ $event->title }}</h3>
                            @if($event->location)
                                <p class="text-sm text-gray-500 flex items-center gap-1.5">
                                    📍 {{ $event->location }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Contact --}}
    @if($page?->content['contact'] ?? null)
        <section class="py-20 px-6 bg-navy text-white" id="contact">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-4">Get In Touch</h2>
                <div class="w-16 h-1 bg-royal mx-auto rounded-full mb-8"></div>
                
                <p class="text-white/70 max-w-md mx-auto mb-6">
                    Registration for both old and new students is ongoing on campus during working hours, Tuesday to Friday.
                </p>
                
                <p class="text-white/70 mb-10">📍 {{ $page->content['contact']['address'] }}</p>

                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($page->content['contact']['phones'] as $phone)
                        <a href="tel:{{ str_replace(' ', '', $phone) }}"
                           class="inline-flex items-center gap-3 bg-white/10 hover:bg-white/20 transition-colors px-8 py-4 rounded-2xl text-lg font-medium">
                            <span>📞</span>
                            {{ $phone }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection