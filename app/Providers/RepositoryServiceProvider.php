<?php

namespace App\Providers;

use App\Repositories\Admin\Images\ImageRepository;
use App\Repositories\Admin\Images\ImageRepositoryInterface;
use App\Repositories\Admin\Categories\CategoryRepository;
use App\Repositories\Admin\Categories\CategoryRepositoryInterface;
use App\Repositories\Admin\Orders\OrderRepository;
use App\Repositories\Admin\Orders\OrderRepositoryInterface;
use App\Repositories\Admin\Permissions\PermissionRepository;
use App\Repositories\Admin\Permissions\PermissionRepositoryInterface;
use App\Repositories\Admin\Products\ProductRepository;
use App\Repositories\Admin\Products\ProductRepositoryInterface;
use App\Repositories\Admin\Roles\RoleRepository;
use App\Repositories\Admin\Roles\RoleRepositoryInterface;
use App\Repositories\Admin\Users\UserRepository;
use App\Repositories\Admin\Users\UserRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
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
