@extends('layouts.app')

@section('title', 'Edit Post — ICC SMS')
@section('header', 'Edit Post')
@section('breadcrumb', 'Blog')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.cms.blog.update', $blog) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Title</label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Excerpt</label>
                    <textarea name="excerpt" rows="2" maxlength="300"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Content</label>
                    <textarea name="content" rows="14"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('content', $blog->content) }}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Status</label>
                    <select name="status"
                            class="w-48 px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                   focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">
                        <option value="draft"     {{ old('status', $blog->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.cms.blog.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection