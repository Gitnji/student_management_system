<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ICC SMS')</title>
    @include('layouts.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-gray min-h-screen">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            @include('layouts.partials.topbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Page Header --}}
                @hasSection('header')
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-navy">@yield('header')</h1>
                        @hasSection('subheader')
                            <p class="text-gray-500 text-sm mt-1">@yield('subheader')</p>
                        @endif
                    </div>
                @endif

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div
        id="sidebar-overlay"
        class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"
        onclick="toggleSidebar()"
    ></div>

    @stack('scripts')
</body>
</html>
