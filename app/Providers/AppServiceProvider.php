<?php

namespace App\Providers;

use App\Models\GradoSeccion;
use App\Observers\GradoSeccionObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        GradoSeccion::observe(GradoSeccionObserver::class);
    }
}
