@extends('public.layout')

@section('title', $post->title . ' — ICC')
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16">
        <a href="{{ route('public.blog') }}" class="text-royal hover:text-blue-700 text-sm font-medium mb-6 inline-block">
            ← Back to News
        </a>
        <p class="text-xs text-gray-400 mb-2">{{ $post->published_at->format('d M Y') }}</p>
        <h1 class="text-3xl font-bold text-navy mb-6">{{ $post->title }}</h1>
        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
@endsection