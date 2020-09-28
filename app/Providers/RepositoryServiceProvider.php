<?php

namespace App\Providers;

use App\Repositories\Admin\Categories\CategoryRepository;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use App\Repositories\Admin\Products\ProductRepository;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
