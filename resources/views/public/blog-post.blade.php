@extends('public.layout')

@section('title', $post->title . ' — Imperial Comprehensive College')
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))

@section('content')

    {{-- Page Header --}}
    <section class="bg-navy text-white py-20 px-6">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('public.blog') }}" 
               class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium mb-8 transition-colors">
                ← Back to All News
            </a>
            
            <p class="text-sky-custom text-sm font-medium mb-3">
                {{ $post->published_at->format('d F Y') }}
            </p>
            
            <h1 class="text-4xl md:text-5xl font-bold leading-tight tracking-tighter">
                {{ $post->title }}
            </h1>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-6 py-12">

        <article class="prose prose-lg max-w-none prose-headings:text-navy prose-headings:font-semibold prose-p:text-gray-700 prose-li:text-gray-700">
            {!! $post->content !!}
        </article>

        {{-- Optional: Share Buttons --}}
        <div class="mt-16 pt-8 border-t border-gray-100">
            <p class="text-sm text-gray-500 mb-4">Share this article:</p>
            <div class="flex gap-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                   target="_blank"
                   class="w-10 h-10 flex items-center justify-center bg-light-gray hover:bg-blue-100 text-navy hover:text-blue-600 rounded-2xl transition-colors">
                    𝕏
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
                   target="_blank"
                   class="w-10 h-10 flex items-center justify-center bg-light-gray hover:bg-sky-100 text-navy hover:text-sky-500 rounded-2xl transition-colors">
                    𝕏
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" 
                   target="_blank"
                   class="w-10 h-10 flex items-center justify-center bg-light-gray hover:bg-blue-100 text-navy hover:text-blue-700 rounded-2xl transition-colors">
                    𝕃
                </a>
            </div>
        </div>

    </div>

@endsection