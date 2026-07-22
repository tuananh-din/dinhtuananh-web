<?php

namespace App\Providers;

use App\Models\About;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);



        view()->composer('*', function ($view) {
            $view->with('contact',About::first());
            $view->with('infor',Setting::first());
        });
    }
}
