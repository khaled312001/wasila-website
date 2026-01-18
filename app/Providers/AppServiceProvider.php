<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Laravel Socialite Service Provider
        if (class_exists(\Laravel\Socialite\SocialiteServiceProvider::class)) {
            $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure timezone is set to Mecca time (Asia/Riyadh)
        // This is already set in config/app.php, but we ensure it here as well
        date_default_timezone_set(config('app.timezone', 'Asia/Riyadh'));
    }
}
