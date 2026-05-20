@extends('layouts.app')

@section('title', 'New Page — ICC SMS')
@section('header', 'New Page')
@section('breadcrumb', 'Pages')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-5">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.cms.pages.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Page Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Slug</label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-sm">/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                               placeholder="e.g. about"
                               class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-sm font-semibold text-navy mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1.5">Meta Title <span class="text-gray-400 font-normal">(max 100 chars)</span></label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="100"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                          focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy mb-1.5">Meta Description <span class="text-gray-400 font-normal">(max 160 chars)</span></label>
                            <textarea name="meta_description" maxlength="160" rows="2"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                             focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                        Create Page
                    </button>
                    <a href="{{ route('admin.cms.pages.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection