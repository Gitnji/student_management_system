<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Imperial Comprehensive College — Bamenda')</title>
    <meta name="description" content="@yield('meta_description', 'Imperial Comprehensive College — Knowledge, Discipline, Excellence')">
    <meta property="og:title" content="@yield('og_title', 'Imperial Comprehensive College')">
    <meta property="og:description" content="@yield('meta_description', '')">
    <meta property="og:type" content="website">
    @include('layouts.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-navy font-sans">

    {{-- Navbar --}}
    <nav class="bg-navy sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-royal flex items-center justify-center transition-transform group-hover:rotate-12">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-bold text-2xl tracking-tighter">ICC</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('public.home') }}"
                       class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('public.home') ? 'text-white font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('public.blog') }}"
                       class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('public.blog*') ? 'text-white font-semibold' : '' }}">
                        News
                    </a>
                    <a href="{{ route('public.events') }}"
                       class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('public.events') ? 'text-white font-semibold' : '' }}">
                        Events
                    </a>
                    <a href="{{ route('public.gallery') }}"
                       class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('public.gallery') ? 'text-white font-semibold' : '' }}">
                        Gallery
                    </a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('login') }}"
                       class="bg-royal hover:bg-blue-600 text-white px-6 py-3 rounded-2xl text-sm font-semibold transition-all active:scale-95">
                        Staff Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-nav-toggle" 
                        class="md:hidden w-11 h-11 flex items-center justify-center text-white rounded-xl hover:bg-white/10 transition-colors">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-nav" class="hidden md:hidden bg-navy border-t border-white/10">
            <div class="px-6 py-8 flex flex-col gap-6 text-lg">
                <a href="{{ route('public.home') }}" class="text-white/80 hover:text-white transition-colors">Home</a>
                <a href="{{ route('public.blog') }}" class="text-white/80 hover:text-white transition-colors">News & Updates</a>
                <a href="{{ route('public.events') }}" class="text-white/80 hover:text-white transition-colors">Events</a>
                <a href="{{ route('public.gallery') }}" class="text-white/80 hover:text-white transition-colors">Gallery</a>
                <a href="{{ route('login') }}" class="text-royal font-semibold">Staff Login →</a>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="bg-navy text-white/70 mt-auto">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                
                <!-- Column 1 -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-2xl bg-royal flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-2xl tracking-tighter">ICC</span>
                    </div>
                    <p class="text-white/60 leading-relaxed">
                        Knowledge, Discipline, Excellence
                    </p>
                    <p class="text-xs mt-6 text-white/50">
                        P.O. Box 421, Bamenda<br>
                        North-West Region, Cameroon
                    </p>
                </div>

                <!-- Column 2 -->
                <div>
                    <h4 class="text-white font-semibold mb-5">Quick Links</h4>
                    <div class="flex flex-col gap-3 text-sm">
                        <a href="{{ route('public.blog') }}" class="hover:text-white transition-colors">Latest News</a>
                        <a href="{{ route('public.events') }}" class="hover:text-white transition-colors">Upcoming Events</a>
                        <a href="{{ route('public.gallery') }}" class="hover:text-white transition-colors">Photo Gallery</a>
                        <a href="#" class="hover:text-white transition-colors">Admissions</a>
                        <a href="#" class="hover:text-white transition-colors">Academic Programs</a>
                    </div>
                </div>

                <!-- Column 3 -->
                <div>
                    <h4 class="text-white font-semibold mb-5">Contact Information</h4>
                    <div class="space-y-3 text-sm">
                        <p class="flex items-start gap-3">
                            <span class="text-royal mt-0.5">📍</span>
                            <span>Azire Old Church, Mankon, Bamenda</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-royal">📞</span>
                            <span>+237 677 345 785</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-royal">📞</span>
                            <span>+237 677 123 626</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-royal">📞</span>
                            <span>+237 654 209 673</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-royal">✉️</span>
                            <span>contact@icc.edu.cm</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 mt-16 pt-8 text-center text-xs text-white/50">
                &copy; {{ date('Y') }} Imperial Comprehensive College. All Rights Reserved. <br><br>
                Powered by <a href="#" class="text-royal hover:text-blue-700">VELA</a>.
            </div>
        </div>
    </footer>

    <script>
        const toggle = document.getElementById('mobile-nav-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const menuIcon = document.getElementById('menu-icon');

        toggle.addEventListener('click', () => {
            mobileNav.classList.toggle('hidden');
            
            // Optional: Change icon to X when open
            if (!mobileNav.classList.contains('hidden')) {
                menuIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12"/>
                `;
            } else {
                menuIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                `;
            }
        });
    </script>

    @stack('scripts')
</body>
</html>