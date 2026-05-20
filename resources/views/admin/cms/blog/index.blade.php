@extends('layouts.app')

@section('title', 'Blog Posts — ICC SMS')
@section('header', 'Blog Posts')
@section('subheader', 'Manage school news and blog posts')
@section('breadcrumb', 'Blog')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.cms.blog.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Post
        </a>
    </div>

    @if($posts->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No blog posts yet.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Title</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Published</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($posts as $post)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">{{ $post->title }}</td>
                            <td class="px-6 py-4">
                                @if($post->status === 'published')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Published</span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $post->published_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.cms.blog.edit', $post) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('admin.cms.blog.destroy', $post) }}"
                                          onsubmit="return confirm('Delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection