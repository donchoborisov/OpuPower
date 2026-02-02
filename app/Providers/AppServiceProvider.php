<?php

namespace App\Providers;

use TCG\Voyager\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        if (app()->runningInConsole()) {
            return;
        }

        try {
            if (!Schema::hasTable('pages')) {
                return;
            }
        } catch (\Throwable $e) {
            return;
        }

        View::share('networkmain', Page::where('title', 'Network Maintenance')->first());
        View::share('itsupport', Page::where('title', 'IT Support Services')->first());
        View::share('networkinst', Page::where('title', 'Network Installation')->first());
        View::share('phone', Page::where('title', 'Telephone Systems')->first());
        View::share('cloud', Page::where('title', 'Cloud Solutions')->first());
        View::share('cctv', Page::where('title', 'CCTV')->first());
    }
}
