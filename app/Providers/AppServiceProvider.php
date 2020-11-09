<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Observers\Admin\Categories\CategoryObserver;
use App\Observers\Admin\Products\ProductObserver;
use App\Observers\Users\UserObserver;
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
        User::observe(UserObserver::class);

        // Share data to inertia

        // This data will be available in global, and can be used anywhere by typing : $page.
        Inertia::share([
            'flash' => fn() => ['messages' => \session()->get('messages')],
        ]);
    }
}
