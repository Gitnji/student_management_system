<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Forbidden</title>
    @include('layouts.partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-light-gray flex items-center justify-center px-4">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-red-600 mb-6">
            <span class="text-white text-3xl font-bold">403</span>
        </div>
        <h1 class="text-2xl font-bold text-navy mb-2">Access denied</h1>
        <p class="text-gray-500 text-sm mb-6">You don't have permission to access this page.</p>
        <a href="{{ url()->previous() }}"
           class="bg-royal hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
            ← Go back
        </a>
    </div>
</body>
</html>
