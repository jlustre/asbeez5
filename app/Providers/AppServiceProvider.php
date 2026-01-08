<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('seller-only', function ($user) {
            return (bool) ($user->is_seller ?? false);
        });

        Gate::define('admin-only', function ($user) {
            return (bool) ($user->is_admin ?? false);
        });
    }
}
