<?php

namespace App\Providers;

use App\Models\Page;
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
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        try {
            if (!Schema::hasTable('pages')) {
                return;
            }
        } catch (\Throwable $e) {
            return;
        }

        if (!app()->runningUnitTests()) {
            $requiredSlugs = [
                'network-maintenance',
                'it-support-services',
                'network-installation',
                'telephone-systems',
                'cloud-solutions',
                'cctv',
            ];

            try {
                $existing = Page::whereIn('slug', $requiredSlugs)->pluck('slug')->all();
                $missing = array_diff($requiredSlugs, $existing);

                if (!empty($missing)) {
                    app(\Database\Seeders\AdminUserSeeder::class)->run();
                    app(\Database\Seeders\PagesTableSeeder::class)->run();
                }
            } catch (\Throwable $e) {
                // Avoid breaking the request if seeding fails.
            }
        }

        View::composer('pages.home', function ($view) {
            $view->with('networkmain', Page::where('slug', 'network-maintenance')->first());
            $view->with('itsupport', Page::where('slug', 'it-support-services')->first());
            $view->with('networkinst', Page::where('slug', 'network-installation')->first());
            $view->with('phone', Page::where('slug', 'telephone-systems')->first());
            $view->with('cloud', Page::where('slug', 'cloud-solutions')->first());
            $view->with('cctv', Page::where('slug', 'cctv')->first());
        });
    }
}
