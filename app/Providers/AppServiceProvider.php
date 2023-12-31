<?php

namespace App\Providers;

use App\Observers\RequestObserver;
use App\Services\RequestFilterService;
use App\Services\RequestService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(RequestService::class, function () {
            return new RequestService();
        });

        $this->app->singleton(RequestObserver::class, function ($app) {
            return new RequestObserver(
                $app->make(RequestService::class)
            );
        });

        $this->app->singleton(RequestFilterService::class, function () {
            return new RequestFilterService();
        });
    }
}
