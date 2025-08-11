<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TableService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TableService::class, function () {
            return new TableService();
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
