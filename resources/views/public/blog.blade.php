@extends('public.layout')

@section('title', 'News & Updates — Imperial Comprehensive College')

@section('content')

    {{-- Page Header --}}
    <section class="bg-navy text-white py-20 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter mb-4">
                News & Updates
            </h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">
                Stay informed with the latest happenings, achievements, and stories from Imperial Comprehensive College
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-16">

        @if($posts->isEmpty())
            <div class="text-center py-24">
                <div class="w-28 h-28 mx-auto bg-light-gray rounded-full flex items-center justify-center mb-8">
                    <span class="text-6xl">📰</span>
                </div>
                <h3 class="text-2xl font-semibold text-navy mb-3">No News Yet</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    We haven't published any articles yet. Please check back soon for exciting updates.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <a href="{{ route('public.blog.post', $post->slug) }}" 
                       class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-royal/20 flex flex-col h-full">
                        
                        <!-- Optional Featured Image -->
                        @if($post->featured_image)
                            <div class="h-52 overflow-hidden">
                                <img src="{{ Storage::url($post->featured_image) }}" 
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif

                        <div class="p-8 flex-1 flex flex-col">
                            <p class="text-xs text-gray-400 mb-3">
                                {{ $post->published_at->format('d M Y') }}
                            </p>
                            
                            <h2 class="font-semibold text-xl text-navy leading-tight mb-4 line-clamp-3 group-hover:text-royal transition-colors">
                                {{ $post->title }}
                            </h2>
                            
                            <p class="text-gray-600 text-sm line-clamp-4 mb-auto">
                                {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}
                            </p>
                            
                            <div class="mt-6 pt-6 border-t border-gray-100 flex items-center text-royal font-medium text-sm">
                                Read Full Story
                                <span class="ml-2 transition-transform group-hover:translate-x-1">→</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $posts->links(data: [
                    'class' => 'inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-white shadow-sm border border-gray-100'
                ]) }}
            </div>
        @endif

    </div>

@endsection