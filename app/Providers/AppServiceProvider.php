<?php

namespace App\Providers;

use app\Services\OtpService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    //     $this->app->singleton(OtpService::class, function ($app) {
    //     return new OtpService();
    // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
