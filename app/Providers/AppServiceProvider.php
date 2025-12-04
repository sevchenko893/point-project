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
    // public function boot()
    // {
    //     // FORCE_HTTPS bisa diatur dari ENV
    //     if (env('FORCE_HTTPS', false)) {
    //         URL::forceScheme('https');
    //     }
    // }

    public function boot()
{
    $host = request()->getHost();

    // Jika host mengandung "ngrok" → paksa HTTPS
    if (str_contains($host, 'ngrok-free.app')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}

}
