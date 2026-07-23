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
            // C-6: query 1 lan/request, cac view sau dung lai (null-safe khi bang rong).
            static $contact = false, $infor = false;

            if ($contact === false) {
                $contact = About::first();
            }

            if ($infor === false) {
                $infor = Setting::first();
            }

            $view->with('contact', $contact);
            $view->with('infor', $infor);
        });
    }
}
