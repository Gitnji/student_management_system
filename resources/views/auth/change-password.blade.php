<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — ICC SMS</title>
    @include('layouts.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-royal mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-white text-2xl font-bold tracking-tight">Set a new password</h1>
            <p class="text-sky-custom text-sm mt-1">You must change your password before continuing.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-6 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-navy mb-1.5">
                        New password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent
                               placeholder:text-gray-400"
                        placeholder="Minimum 8 characters"
                    />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-navy mb-1.5">
                        Confirm new password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-light-gray text-navy text-sm
                               focus:outline-none focus:ring-2 focus:ring-royal focus:border-transparent
                               placeholder:text-gray-400"
                        placeholder="Repeat your password"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-royal hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg
                           transition-colors duration-150 text-sm"
                >
                    Set password and continue
                </button>
            </form>
        </div>
    </div>

</body>
</html>
