<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $events = Event::where('school_id', $school->id)
            ->orderByDesc('start_date')
            ->get();
        return view('admin.cms.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.cms.events.create');
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'location'    => ['nullable', 'string', 'max:200'],
        ]);

        $data['school_id'] = $school->id;

        Event::create($data);

        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);
        return view('admin.cms.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'location'    => ['nullable', 'string', 'max:200'],
        ]);

        $event->update($data);

        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);
        $event->delete();
        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event deleted.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->school_id !== Auth::user()->school_id) {
            abort(403);
        }
    }
}