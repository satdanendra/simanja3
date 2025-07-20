<?php

namespace App\Providers;

use App\Services\PegawaiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PegawaiService
        $this->app->singleton(PegawaiService::class, function ($app) {
            return new PegawaiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
