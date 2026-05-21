<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $pages  = Page::where('school_id', $school->id)->orderBy('title')->get();
        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.pages.create');
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:100'],
            'slug'             => ['required', 'string', 'max:100'],
            'meta_title'       => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'content'          => ['nullable', 'array'],
        ]);

        $data['slug']      = Str::slug($data['slug']);
        $data['school_id'] = $school->id;

        // Ensure slug is unique
        if (Page::where('slug', $data['slug'])->exists()) {
            return back()->withInput()
                ->withErrors(['slug' => 'This slug is already taken.']);
        }

        Page::create($data);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $this->authorizePage($page);
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
{
    $this->authorizePage($page);

    $data = $request->validate([
        'title' => ['required', 'string', 'max:100'],
        'meta_title' => ['nullable', 'string', 'max:100'],
        'meta_description' => ['nullable', 'string', 'max:160'],
        'hero_heading' => ['nullable', 'string'],
        'hero_subtext' => ['nullable', 'string'],
        'hero_cta_text' => ['nullable', 'string'],
        'hero_cta_link' => ['nullable', 'string'],
        'about_text' => ['nullable', 'string'],
        'stats' => ['nullable', 'array'],
        'stats.*.value' => ['nullable', 'string'],
        'stats.*.label' => ['nullable', 'string'],
    ]);

    // Get current content safely
    $currentContent = $page->content ? (is_array($page->content) ? $page->content : json_decode($page->content, true)) : [];

    // Start building the new content by preserving existing data
    $content = $currentContent;

    // === Hero Section ===
    if ($request->hasAny(['hero_heading', 'hero_subtext', 'hero_cta_text', 'hero_cta_link'])) {
        $content['hero'] = [
            'heading'   => $request->input('hero_heading', $currentContent['hero']['heading'] ?? ''),
            'subtext'   => $request->input('hero_subtext', $currentContent['hero']['subtext'] ?? ''),
            'cta_text'  => $request->input('hero_cta_text', $currentContent['hero']['cta_text'] ?? ''),
            'cta_link'  => $request->input('hero_cta_link', $currentContent['hero']['cta_link'] ?? ''),
        ];
    }

    // === About Section ===
    if ($request->filled('about_text') || isset($currentContent['about'])) {
        $content['about'] = [
            'text' => $request->input('about_text', $currentContent['about']['text'] ?? ''),
        ];
    }

    // === Stats Section ===
    if ($request->has('stats')) {
        $content['stats'] = $request->input('stats');
    }

    // Add more sections here later (programs, facilities, contact, etc.)

    $data['content'] = $content;

    $page->update($data);

    return redirect()->route('admin.cms.pages.index')
        ->with('success', 'Page updated successfully.');
}
    public function destroy(Page $page)
    {
        $this->authorizePage($page);
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted.');
    }

    private function authorizePage(Page $page): void
    {
        if ($page->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}