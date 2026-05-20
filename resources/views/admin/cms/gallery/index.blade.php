@extends('layouts.app')

@section('title', 'Gallery — ICC SMS')
@section('header', 'Gallery')
@section('subheader', 'Manage photos and videos for the school website')
@section('breadcrumb', 'Gallery')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Photos --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-navy mb-4">Upload Photo</h3>

                <form method="POST" action="{{ route('admin.cms.gallery.photos.store') }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Photo</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-sm text-gray-500
                                      file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-medium file:bg-royal file:text-white
                                      hover:file:bg-blue-700 cursor-pointer" />
                        @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Caption <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="caption" value="{{ old('caption') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>

                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        Upload Photo
                    </button>
                </form>
            </div>

            {{-- Photo Grid --}}
            @if($photos->isNotEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-navy mb-4">Photos ({{ $photos->count() }})</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($photos as $photo)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-100">
                                <img src="{{ Storage::url($photo->image_path) }}"
                                     alt="{{ $photo->caption }}"
                                     class="w-full h-32 object-cover" />
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form method="POST" action="{{ route('admin.cms.gallery.photos.destroy', $photo) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Delete this photo?')"
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                @if($photo->caption)
                                    <p class="px-2 py-1 text-xs text-gray-500 truncate">{{ $photo->caption }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Videos --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-navy mb-4">Add Video</h3>

                <form method="POST" action="{{ route('admin.cms.gallery.videos.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Video URL</label>
                        <input type="url" name="url" value="{{ old('url') }}"
                               placeholder="https://youtube.com/watch?v=..."
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                        @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy mb-1.5">Caption <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="caption" value="{{ old('caption') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                                      focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent" />
                    </div>

                    <button type="submit"
                            class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        Add Video
                    </button>
                </form>
            </div>

            {{-- Video List --}}
            @if($videos->isNotEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-navy mb-4">Videos ({{ $videos->count() }})</h3>
                    <div class="space-y-3">
                        @foreach($videos as $video)
                            <div class="flex items-start justify-between gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-navy truncate">{{ $video->title }}</p>
                                    <a href="{{ $video->url }}" target="_blank"
                                       class="text-xs text-royal hover:underline truncate block">
                                        {{ $video->url }}
                                    </a>
                                    @if($video->caption)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $video->caption }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.cms.gallery.videos.destroy', $video) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove this video?')"
                                            class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors flex-shrink-0">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection