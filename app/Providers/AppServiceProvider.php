<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('register', function (Request $request) {
            return Limit::perDay(1)->by($request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perHour(5)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        RateLimiter::for('upload_profile', function (Request $request) {
            return Limit::perDay(2)->by(
                $request->user()->id
            );
        });
    }
}
