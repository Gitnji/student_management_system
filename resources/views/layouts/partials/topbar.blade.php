<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">

    {{-- Mobile menu toggle --}}
    <button
        onclick="toggleSidebar()"
        class="lg:hidden text-navy hover:text-royal transition-colors"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Breadcrumb slot --}}
    <div class="hidden lg:flex items-center gap-2 text-sm text-gray-500">
        <span class="font-medium text-navy">ICC SMS</span>
        @hasSection('breadcrumb')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            @yield('breadcrumb')
        @endif
    </div>

    {{-- Right side --}}
    <div class="flex items-center gap-4 ml-auto">
        <span class="text-sm text-gray-500 hidden sm:block">
            {{ now()->format('l, d M Y') }}
        </span>
        <div class="w-8 h-8 rounded-full bg-royal flex items-center justify-center">
            <span class="text-white text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
            </span>
        </div>
    </div>
</header>