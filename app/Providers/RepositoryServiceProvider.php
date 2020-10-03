<?php

namespace App\Providers;

use App\Repositories\Admin\API\Images\ImageRepository;
use App\Repositories\Admin\API\Images\ImageRepositoryInterface;
use App\Repositories\Admin\Categories\CategoryRepository;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use App\Repositories\Admin\Products\ProductRepository;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Services\Admin\Images\ImageService;
use App\Services\Admin\Interfaces\Images\ImageServiceInterface;
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
        // Repositories

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);

        // Services

        $this->app->bind(ImageServiceInterface::class, ImageService::class);
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
