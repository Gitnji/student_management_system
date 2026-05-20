<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Imperial Comprehensive College')</title>
    <meta name="description" content="@yield('meta_description', 'Imperial Comprehensive College — Knowledge, Discipline, Excellence')">
    <meta property="og:title" content="@yield('og_title', 'Imperial Comprehensive College')">
    <meta property="og:description" content="@yield('meta_description', '')">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-navy">

    {{-- Navbar --}}
    <nav class="bg-navy sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">

            <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-royal flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-sm tracking-wide">ICC</span>
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('public.home') }}"
                   class="text-white/70 hover:text-white transition-colors {{ request()->routeIs('public.home') ? 'text-white font-medium' : '' }}">
                    Home
                </a>
                <a href="{{ route('public.blog') }}"
                   class="text-white/70 hover:text-white transition-colors {{ request()->routeIs('public.blog*') ? 'text-white font-medium' : '' }}">
                    News
                </a>
                <a href="{{ route('public.events') }}"
                   class="text-white/70 hover:text-white transition-colors {{ request()->routeIs('public.events') ? 'text-white font-medium' : '' }}">
                    Events
                </a>
                <a href="{{ route('public.gallery') }}"
                   class="text-white/70 hover:text-white transition-colors {{ request()->routeIs('public.gallery') ? 'text-white font-medium' : '' }}">
                    Gallery
                </a>
                <a href="{{ route('login') }}"
                   class="bg-royal hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    Staff Login
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button id="mobile-nav-toggle" class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-nav" class="hidden md:hidden bg-navy border-t border-white/10 px-4 pb-4">
            <div class="flex flex-col gap-3 pt-3 text-sm">
                <a href="{{ route('public.home') }}"    class="text-white/70 hover:text-white transition-colors">Home</a>
                <a href="{{ route('public.blog') }}"    class="text-white/70 hover:text-white transition-colors">News</a>
                <a href="{{ route('public.events') }}"  class="text-white/70 hover:text-white transition-colors">Events</a>
                <a href="{{ route('public.gallery') }}" class="text-white/70 hover:text-white transition-colors">Gallery</a>
                <a href="{{ route('login') }}"          class="text-white/70 hover:text-white transition-colors">Staff Login</a>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="bg-navy text-white/60 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-semibold mb-3 text-sm">Imperial Comprehensive College</h4>
                    <p class="text-xs leading-relaxed">"Knowledge, Discipline, Excellence"<br>P.O. Box 421, Bamenda<br>North-West Region, Cameroon</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3 text-sm">Quick Links</h4>
                    <div class="flex flex-col gap-2 text-xs">
                        <a href="{{ route('public.blog') }}"    class="hover:text-white transition-colors">News & Updates</a>
                        <a href="{{ route('public.events') }}"  class="hover:text-white transition-colors">Events</a>
                        <a href="{{ route('public.gallery') }}" class="hover:text-white transition-colors">Gallery</a>
                        <a href="{{ route('login') }}"          class="hover:text-white transition-colors">Staff Portal</a>
                    </div>
                </div>
                <div>
    <h4 class="text-white font-semibold mb-3 text-sm">Contact</h4>
    <div class="text-xs space-y-1">
        <p>📍 Azire Old Church, Mankon, Bamenda</p>
        <p>Tel: +237 677 345 785</p>
        <p>Tel: +237 677 123 626</p>
        <p>Tel: +237 654 209 673</p>
        <p>contact@icc.edu.cm</p>
        <p class="text-white/40 mt-2">Reg: Tue – Fri, Working Hours</p>
    </div>
</div>
            </div>
            <div class="border-t border-white/10 pt-6 text-xs text-center">
                &copy; {{ date('Y') }} Imperial Comprehensive College. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-nav-toggle').addEventListener('click', function() {
            document.getElementById('mobile-nav').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>