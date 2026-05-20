@extends('layouts.app')

@section('title', 'Events — ICC SMS')
@section('header', 'Events')
@section('subheader', 'Manage school events calendar')
@section('breadcrumb', 'Events')

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.cms.events.create') }}"
           class="inline-flex items-center gap-2 bg-royal hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Event
        </a>
    </div>

    @if($events->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">No events yet.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-light-gray">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Event</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Start Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">End Date</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Location</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($events as $event)
                        @php $isPast = $event->start_date->isPast(); @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ $isPast ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 font-medium text-navy">
                                {{ $event->title }}
                                @if($isPast)
                                    <span class="ml-2 text-xs text-gray-400">(past)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $event->start_date->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $event->end_date?->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $event->location ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.cms.events.edit', $event) }}"
                                       class="text-royal hover:text-blue-700 text-sm font-medium transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('admin.cms.events.destroy', $event) }}"
                                          onsubmit="return confirm('Delete this event?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection