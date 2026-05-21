@extends('public.layout')

@section('title', 'Gallery — Imperial Comprehensive College')

@section('content')

    {{-- Page Header --}}
    <section class="bg-navy text-white py-20 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold tracking-tighter mb-4">
                Our Gallery
            </h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">
                Moments of excellence, learning, and celebration at Imperial Comprehensive College
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-6 py-16">

        @if($photos->isNotEmpty())
            <div class="mb-16">
                <div class="flex items-center gap-3 mb-8">
                    <h2 class="text-3xl font-bold text-navy">Photos</h2>
                    <div class="flex-1 h-px bg-gradient-to-r from-royal/30 to-transparent"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($photos as $photo)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <div class="aspect-square overflow-hidden">
                                <img src="{{ Storage::url($photo->image_path) }}"
                                     alt="{{ $photo->caption ?? 'ICC Gallery' }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            </div>
                            @if($photo->caption)
                                <div class="p-5">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $photo->caption }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($videos->isNotEmpty())
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <h2 class="text-3xl font-bold text-navy">Videos</h2>
                    <div class="flex-1 h-px bg-gradient-to-r from-royal/30 to-transparent"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($videos as $video)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <div class="aspect-video bg-light-gray relative flex items-center justify-center overflow-hidden">
                                <!-- Video Thumbnail Placeholder -->
                                <div class="w-20 h-20 bg-royal/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/50">
                                    <svg class="w-10 h-10 text-royal" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="font-semibold text-navy mb-2 line-clamp-2">{{ $video->title }}</h3>
                                @if($video->caption)
                                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $video->caption }}</p>
                                @endif
                                
                                <a href="{{ $video->url }}" target="_blank"
                                   class="inline-flex items-center gap-2 text-royal hover:text-blue-600 font-medium text-sm transition-colors">
                                    <span>Watch Video</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($photos->isEmpty() && $videos->isEmpty())
            <div class="text-center py-20">
                <div class="w-24 h-24 mx-auto bg-light-gray rounded-full flex items-center justify-center mb-6">
                    <span class="text-4xl">📷</span>
                </div>
                <h3 class="text-xl font-medium text-navy mb-2">Gallery is Empty</h3>
                <p class="text-gray-500 max-w-xs mx-auto">
                    No media has been added yet. Check back later for exciting moments from ICC.
                </p>
            </div>
        @endif

    </div>

@endsection