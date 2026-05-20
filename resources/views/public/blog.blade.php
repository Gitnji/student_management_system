@extends('public.layout')

@section('title', 'News & Updates — ICC')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-navy mb-8">News & Updates</h1>

        @if($posts->isEmpty())
            <p class="text-gray-400">No posts yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <a href="{{ route('public.blog.post', $post->slug) }}"
                       class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow block">
                        <p class="text-xs text-gray-400 mb-2">{{ $post->published_at->format('d M Y') }}</p>
                        <h2 class="font-semibold text-navy mb-2 line-clamp-2">{{ $post->title }}</h2>
                        <p class="text-gray-500 text-sm line-clamp-3">
                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                        </p>
                        <span class="text-royal text-sm font-medium mt-3 inline-block">Read more →</span>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection