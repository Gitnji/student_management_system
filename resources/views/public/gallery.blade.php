@extends('public.layout')

@section('title', 'Gallery — ICC')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-navy mb-10">Gallery</h1>

        @if($photos->isNotEmpty())
            <h2 class="text-lg font-semibold text-navy mb-4">Photos</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
                @foreach($photos as $photo)
                    <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                        <img src="{{ Storage::url($photo->image_path) }}"
                             alt="{{ $photo->caption }}"
                             class="w-full h-40 object-cover hover:scale-105 transition-transform duration-200" />
                        @if($photo->caption)
                            <p class="px-3 py-2 text-xs text-gray-500">{{ $photo->caption }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if($videos->isNotEmpty())
            <h2 class="text-lg font-semibold text-navy mb-4">Videos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($videos as $video)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                        <h3 class="font-medium text-navy text-sm mb-2">{{ $video->title }}</h3>
                        @if($video->caption)
                            <p class="text-xs text-gray-400 mb-3">{{ $video->caption }}</p>
                        @endif
                        <a href="{{ $video->url }}" target="_blank"
                           class="inline-flex items-center gap-2 text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            Watch Video
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if($photos->isEmpty() && $videos->isEmpty())
            <p class="text-gray-400">No gallery items yet.</p>
        @endif
    </div>
@endsection