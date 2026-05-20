@extends('public.layout')

@section('title', $page?->meta_title ?? 'Imperial Comprehensive College')
@section('meta_description', $page?->meta_description ?? '')

@section('content')

    {{-- Hero --}}
    <section class="bg-navy text-white py-24 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                {{ $page?->content['hero']['heading'] ?? 'Excellence in Education' }}
            </h1>
            <p class="text-white/70 text-lg mb-8 max-w-2xl mx-auto">
                {{ $page?->content['hero']['subtext'] ?? '' }}
            </p>
            @if($page?->content['hero']['cta_text'] ?? null)
                <a href="{{ $page->content['hero']['cta_link'] ?? '#' }}"
                   class="inline-block bg-royal hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                    {{ $page->content['hero']['cta_text'] }}
                </a>
            @endif
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
                <p class="text-gray-600 leading-relaxed">{{ $page->content['about']['text'] }}</p>
            </div>
        </section>
    @endif

    {{-- Latest News --}}
    @if($posts->isNotEmpty())
        <section class="py-16 px-4 bg-light-gray">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-navy">Latest News</h2>
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
                            <p class="text-gray-500 text-sm line-clamp-3">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}</p>
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
                    <h2 class="text-2xl font-bold text-navy">Upcoming Events</h2>
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

@endsection