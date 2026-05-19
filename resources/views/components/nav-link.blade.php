@props(['route', 'icon'])

@php
    $active = request()->routeIs($route . '*');
    try {
        $url = route($route);
    } catch (\Exception $e) {
        $url = '#';
    }
@endphp

<a
    href="{{ $url }}"
    @class([
        'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 mb-0.5',
        'bg-royal text-white'                              => $active,
        'text-white/60 hover:text-white hover:bg-white/10' => !$active,
    ])
>
    @include('layouts.partials.icons.' . $icon)
    {{ $slot }}
</a>