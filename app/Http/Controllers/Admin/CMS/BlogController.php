<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $posts  = BlogPost::where('school_id', $school->id)
            ->orderByDesc('created_at')
            ->get();
        return view('admin.cms.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.cms.blog.create');
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'status'  => ['required', 'in:draft,published'],
        ]);

        $data['school_id']  = $school->id;
        $data['author_id']  = Auth::id();
        $data['slug']       = $this->uniqueSlug($data['title']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        BlogPost::create($data);

        return redirect()->route('admin.cms.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog)
    {
        $this->authorizePost($blog);
        return view('admin.cms.blog.edit', compact('blog'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $this->authorizePost($blog);

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'status'  => ['required', 'in:draft,published'],
        ]);

        // Set published_at only when first publishing
        if ($data['status'] === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $blog->update($data);

        return redirect()->route('admin.cms.blog.index')
            ->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        $this->authorizePost($blog);
        $blog->delete();
        return redirect()->route('admin.cms.blog.index')
            ->with('success', 'Post deleted.');
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = BlogPost::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    private function authorizePost(BlogPost $blog): void
    {
        if ($blog->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}