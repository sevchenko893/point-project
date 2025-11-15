<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <<--- tambahkan ini

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
    public function boot()
    {
        // FORCE_HTTPS bisa diatur dari ENV
        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }

}
