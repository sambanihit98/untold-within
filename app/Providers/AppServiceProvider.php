<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Forces all generated URLs to use HTTPS in production to prevent redirect issues and mixed content
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
