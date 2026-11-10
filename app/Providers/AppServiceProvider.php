<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(
            ['layouts.public', 'public.*'],
            \App\View\Composers\MenuComposer::class
        );

        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        \Illuminate\Support\Facades\RateLimiter::for('public-forms', function ($request) {
            return [\Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip())];
        });
    }
}
