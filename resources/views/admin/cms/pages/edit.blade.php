@extends('layouts.app')

@section('title', 'Edit Page — ICC SMS')
@section('header', 'Edit: ' . $page->title)
@section('breadcrumb', 'Pages')

@section('content')
    <div class="max-w-3xl space-y-6">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.cms.pages.update', $page) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
                <h3 class="text-sm font-semibold text-navy">Page Info</h3>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>URL:</span>
                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">/{{ $page->slug }}</span>
                </div>
            </div>

            {{-- Hero Section --}}
            @if($page->slug === 'home')
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-navy">Hero Section</h3>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Heading</label>
                    <input type="text" name="hero_heading"
                           value="{{ old('hero_heading', $page->content['hero']['heading'] ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Subtext</label>
                    <textarea name="hero_subtext" rows="2"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('hero_subtext', $page->content['hero']['subtext'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">CTA Button Text</label>
                        <input type="text" name="hero_cta_text"
                               value="{{ old('hero_cta_text', $page->content['hero']['cta_text'] ?? '') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">CTA Link</label>
                        <input type="text" name="hero_cta_link"
                               value="{{ old('hero_cta_link', $page->content['hero']['cta_link'] ?? '') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>
                </div>
            </div>

            {{-- About Section --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-navy">About Section</h3>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Text</label>
                    <textarea name="about_text" rows="4"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('about_text', $page->content['about']['text'] ?? '') }}</textarea>
                </div>
            </div>

            {{-- Stats Section --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-navy">Stats Section</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach(($page->content['stats'] ?? [['label'=>'','value'=>''],['label'=>'','value'=>''],['label'=>'','value'=>''],['label'=>'','value'=>'']]) as $i => $stat)
                        <div class="flex gap-2">
                            <input type="text" name="stats[{{ $i }}][label]"
                                   value="{{ old("stats.{$i}.label", $stat['label']) }}"
                                   placeholder="Label"
                                   class="flex-1 px-3 py-2 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                          focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                            <input type="text" name="stats[{{ $i }}][value]"
                                   value="{{ old("stats.{$i}.value", $stat['value']) }}"
                                   placeholder="Value"
                                   class="w-24 px-3 py-2 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                          focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- SEO --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-semibold text-navy">SEO Settings</h3>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" maxlength="100"
                           value="{{ old('meta_title', $page->meta_title) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                  focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1.5">Meta Description</label>
                    <textarea name="meta_description" maxlength="160" rows="2"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                     focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.cms.pages.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection