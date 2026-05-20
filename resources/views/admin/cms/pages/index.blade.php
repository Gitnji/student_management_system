@extends('layouts.app')

@section('title', 'Pages — ICC SMS')
@section('header', 'CMS Pages')
@section('subheader', 'Manage your school website pages')
@section('breadcrumb', 'Pages')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.cms.pages.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Page
        </a>
    </div>

    @if($pages->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No pages yet.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Title</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Slug</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Meta Title</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pages as $page)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy">{{ $page->title }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">/{{ $page->slug }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $page->meta_title ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.cms.pages.edit', $page) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <a href="{{ url('/' . $page->slug) }}" target="_blank"
                                       class="text-gray-500 hover:text-navy text-sm font-medium transition-colors">
                                        View
                                    </a>
                                    @if($page->slug !== 'home')
                                        <form method="POST" action="{{ route('admin.cms.pages.destroy', $page) }}"
                                              onsubmit="return confirm('Delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection