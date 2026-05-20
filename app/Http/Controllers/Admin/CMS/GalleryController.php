<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $photos = GalleryPhoto::where('school_id', $school->id)->orderBy('sort_order')->get();
        $videos = GalleryVideo::where('school_id', $school->id)->orderBy('sort_order')->get();
        return view('admin.cms.gallery.index', compact('photos', 'videos'));
    }

    // --- Photos ---

    public function storePhoto(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'photo'   => ['required', 'image', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:200'],
        ]);

        $path = $request->file('photo')->store('gallery', 'public');

        GalleryPhoto::create([
            'school_id'  => $school->id,
            'image_path' => $path,
            'caption'    => $request->caption,
            'sort_order' => GalleryPhoto::where('school_id', $school->id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroyPhoto(GalleryPhoto $photo)
    {
        if ($photo->school_id !== Auth::user()->school_id) abort(403);
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        return back()->with('success', 'Photo deleted.');
    }

    // --- Videos ---

    public function storeVideo(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'title'   => ['required', 'string', 'max:200'],
            'url'     => ['required', 'url'],
            'caption' => ['nullable', 'string', 'max:200'],
        ]);

        GalleryVideo::create([
            'school_id'  => $school->id,
            'title'      => $request->title,
            'url'        => $request->url,
            'caption'    => $request->caption,
            'sort_order' => GalleryVideo::where('school_id', $school->id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Video added successfully.');
    }

    public function destroyVideo(GalleryVideo $video)
    {
        if ($video->school_id !== Auth::user()->school_id) abort(403);
        $video->delete();
        return back()->with('success', 'Video removed.');
    }
}