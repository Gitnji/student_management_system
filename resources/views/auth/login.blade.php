<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ICC School Management</title>
    @include('layouts.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-royal mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-white text-2xl font-bold tracking-tight">Imperial Comprehensive College</h1>
            <p class="text-sky-custom text-sm mt-1">School Management System</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-navy text-xl font-semibold mb-6">Sign in to your account</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-navy mb-1.5">
                        Email address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent
                               placeholder:text-gray-400"
                        placeholder="you@icc.cm"
                    />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-navy mb-1.5">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent
                               placeholder:text-gray-400"
                        placeholder="••••••••"
                    />
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 text-royal border-gray-300 rounded focus:ring-royal">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-royal hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg
                           transition-colors duration-150 text-sm"
                >
                    Sign in
                </button>
                 <a href="{{ route('public.home') }}" class="text-royal hover:text-blue-700 text-sm">Back to Home</a>
            </form>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            &copy; {{ date('Y') }} Imperial Comprehensive College. All rights reserved.
        </p>
    </div>

</body>
</html>
