<?php

namespace App\Providers;

use App\Models\ClassLevel;
use App\Models\School;
use App\Models\Stream;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Cache static lookup data for 24 hours
        Cache::remember('class_levels', 86400, fn() => ClassLevel::orderBy('order')->get());
        Cache::remember('streams', 86400, fn() => Stream::orderBy('name')->get());
        Cache::remember('school_data', 86400, fn() => School::first()?->toArray());
    }
}