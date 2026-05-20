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
            'title'            => ['required', 'string', 'max:100'],
            'meta_title'       => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ]);

        // Build content from sections
        $content = [];
        if ($request->filled('hero_heading')) {
            $content['hero'] = [
                'heading'  => $request->hero_heading,
                'subtext'  => $request->hero_subtext,
                'cta_text' => $request->hero_cta_text,
                'cta_link' => $request->hero_cta_link,
            ];
        }
        if ($request->filled('about_text')) {
            $content['about'] = [
                'text' => $request->about_text,
            ];
        }
        if ($request->filled('stats')) {
            $content['stats'] = $request->stats;
        }

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