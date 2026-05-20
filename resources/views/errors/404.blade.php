<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-light-gray flex items-center justify-center px-4">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-navy mb-6">
            <span class="text-white text-3xl font-bold">404</span>
        </div>
        <h1 class="text-2xl font-bold text-navy mb-2">Page not found</h1>
        <p class="text-gray-500 text-sm mb-6">The page you are looking for doesn't exist or has been moved.</p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ url()->previous() }}"
               class="text-sm font-medium text-gray-500 hover:text-navy transition-colors">
                ← Go back
            </a>
            <a href="{{ route('public.home') }}"
               class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Go to homepage
            </a>
        </div>
    </div>
</body>
</html>