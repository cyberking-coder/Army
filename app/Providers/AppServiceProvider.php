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
        $this->swallowPhpNotices();

        \Illuminate\Support\Facades\View::composer(
            ['layouts.public', 'public.*'],
            \App\View\Composers\MenuComposer::class
        );

        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        \Illuminate\Support\Facades\RateLimiter::for('public-forms', function ($request) {
            return [\Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip())];
        });
    }

    /**
     * Laravel's HandleExceptions bootstrap sets error_reporting(-1) and wires
     * set_error_handler() to convert every PHP notice/deprecation into an
     * ErrorException. PHP 8.4 added an E_NOTICE to tempnam() when the AWS SDK
     * falls back to the system temp dir on R2 uploads, which killed uploads.
     *
     * Wrap Laravel's handler so notices and deprecations are silently
     * swallowed; real warnings and errors still reach Laravel.
     */
    protected function swallowPhpNotices(): void
    {
        $previous = set_error_handler(null);
        set_error_handler(function ($level, $message, $file = '', $line = 0) use ($previous) {
            if (in_array($level, [E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED], true)) {
                return true;
            }
            if ($previous) {
                return $previous($level, $message, $file, $line);
            }
            return false;
        });
    }
}
}
