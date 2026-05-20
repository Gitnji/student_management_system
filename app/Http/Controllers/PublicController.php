<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\Page;
use App\Models\School;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    private function school(): School
    {
        return School::first();
    }

    public function home()
    {
        $school  = $this->school();
        $page    = Page::where('school_id', $school->id)->where('slug', 'home')->first();
        $posts   = BlogPost::where('school_id', $school->id)->where('status', 'published')->orderByDesc('published_at')->take(3)->get();
        $events  = Event::where('school_id', $school->id)->where('start_date', '>=', now())->orderBy('start_date')->take(4)->get();

        return view('public.home', compact('school', 'page', 'posts', 'events'));
    }

    public function blog()
    {
        $school = $this->school();
        $posts  = BlogPost::where('school_id', $school->id)->where('status', 'published')->orderByDesc('published_at')->paginate(9);

        return view('public.blog', compact('school', 'posts'));
    }

    public function blogPost(string $slug)
    {
        $school = $this->school();
        $post   = BlogPost::where('school_id', $school->id)->where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('public.blog-post', compact('school', 'post'));
    }

    public function events()
    {
        $school         = $this->school();
        $upcomingEvents = Event::where('school_id', $school->id)->where('start_date', '>=', now())->orderBy('start_date')->get();
        $pastEvents     = Event::where('school_id', $school->id)->where('start_date', '<', now())->orderByDesc('start_date')->take(6)->get();

        return view('public.events', compact('school', 'upcomingEvents', 'pastEvents'));
    }

    public function gallery()
    {
        $school = $this->school();
        $photos = GalleryPhoto::where('school_id', $school->id)->orderBy('sort_order')->get();
        $videos = GalleryVideo::where('school_id', $school->id)->orderBy('sort_order')->get();

        return view('public.gallery', compact('school', 'photos', 'videos'));
    }
}