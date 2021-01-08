<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share data to inertia

        // This data will be available in global, and can be used anywhere by typing : $page.
        Inertia::share([
            'flash' => fn() => [
                'messages' => \session()->get('messages')
            ],

            'locale' => $this->app->getLocale(),

            'translations' => cache('translations')
        ]);
    }
}
