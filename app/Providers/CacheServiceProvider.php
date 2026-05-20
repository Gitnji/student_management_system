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
        $this->forgetInvalidLookup('class_levels');
        $this->forgetInvalidLookup('streams');

        // Cache static lookup data for 24 hours
        Cache::remember('class_levels', 86400, fn() => ClassLevel::orderBy('order')->get());
        Cache::remember('streams', 86400, fn() => Stream::orderBy('name')->get());
        Cache::remember('school_data', 86400, fn() => School::first()?->toArray());
    }

    private function forgetInvalidLookup(string $key): void
    {
        $items = Cache::get($key);

        if ($items === null) {
            return;
        }

        if (!is_iterable($items)) {
            Cache::forget($key);
            return;
        }

        foreach ($items as $item) {
            if (!is_object($item) || !isset($item->id, $item->name)) {
                Cache::forget($key);
                return;
            }
        }
    }
}
