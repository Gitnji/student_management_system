<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('admin', fn(User $user) => $user->isAdmin() && $user->is_active);
        Gate::define('teacher', fn(User $user) => $user->isTeacher() && $user->is_active);
    }
}