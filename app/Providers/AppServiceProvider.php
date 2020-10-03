<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Observers\Admin\Categories\CategoryObserver;
use App\Observers\Admin\Products\ProductObserver;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        // Observers
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);

        // Inertia flash messages
        Inertia::share('flash', function () {
            return [
                'messages' => \session()->get('messages'),
            ];
        });
    }
}
