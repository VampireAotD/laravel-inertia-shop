<?php

use App\Http\Controllers\Admin\Home\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\Images\ImageController;
use App\Http\Controllers\Admin\Categories\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Permissions\PermissionController;
use App\Http\Controllers\Admin\Products\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Orders\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\Roles\RoleController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\API\Facebook\FacebookLoginController;
use App\Http\Controllers\API\Frontend\RecentViewsController;
use App\Http\Controllers\Frontend\Cart\CartController;
use App\Http\Controllers\Frontend\Favorite\FavoriteController;
use App\Http\Controllers\Frontend\Home\HomeController;
use App\Http\Controllers\Frontend\Language\LanguageController;
use App\Http\Controllers\Frontend\Product\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin routes

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum', 'role:admin|moderator', 'user-data']], function () {

    // Dashboard

    Route::get('/', [AdminHomeController::class, 'index'])
        ->middleware('permission:view dashboard')
        ->name('dashboard');

    // Users

    Route::get('users/search', [UserController::class, 'search'])->name('users.search');

    Route::patch('/users/{user}', [UserController::class, 'changeRole'])
        ->middleware('permission:change user role')
        ->name('users.change-role');

    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'destroy'])
        ->middleware('permission:view users list|view user|delete user')
        ->names('users');

    // Roles

    Route::resource('roles', RoleController::class)
        ->middleware('role:admin')
        ->names('roles');

    // Permissions

    Route::resource('permissions', PermissionController::class)
        ->middleware('role:admin')
        ->names('permissions');

    // Categories

    Route::get('categories/search', [AdminCategoryController::class, 'search'])->name('categories.search');

    Route::resource('categories', AdminCategoryController::class)
        ->middleware('permission:view categories list|view category|create category|edit category|delete category')
        ->names('categories');

    // Products

    Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');

    Route::resource('products', AdminProductController::class)
        ->middleware('permission:view products list|view product|create product|edit product|delete product')
        ->names('products');

    // Images

    Route::get('/images/{image}', [ImageController::class, 'updateImage'])
        ->middleware('permission:update product main image')
        ->name('images.update-main-image');

    Route::delete('/images/{image}', [ImageController::class, 'destroyImage'])
        ->middleware('permission:remove product images')
        ->name('images.destroy-image');

    // Orders

    Route::resource('orders', AdminOrderController::class)
        ->only(['index', 'destroy'])
        ->middleware('permission:view orders list|accept order|cancel order|delete order')
        ->names('orders');

    Route::get('/orders/{user}/{date}', [AdminOrderController::class, 'show'])
        ->middleware('permission:view order')
        ->name('orders.show');

    Route::get('/accept-order/{order}', [AdminOrderController::class, 'accept'])
        ->middleware('permission:accept order')
        ->name('orders.accept');
});

// Frontend routes

Route::group(['middleware' => ['favorite-list', 'cart']], function () {

    // Home

    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Facebook Auth

    Route::get('/facebook/login', [FacebookLoginController::class, 'redirect'])
        ->middleware('guest')
        ->name('facebook-login');

    Route::get('/facebook/callback', [FacebookLoginController::class, 'callback'])
        ->middleware('guest')
        ->name('facebook-callback');

    // Profile and API Tokens

    Route::get('user/profile', [\Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('user/api-tokens', [\Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController::class, 'index'])
        ->name('api-tokens.index');

    // Change language

    Route::get('/change-language/{lang}', LanguageController::class)
        ->name('change-language');

    // Product

    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product');

    // Cart

    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::get('/cart/order', [CartController::class, 'order'])
        ->middleware('auth')
        ->name('order');

    Route::get('/add-to-cart/{product}', [CartController::class, 'add'])->name('add-to-cart');

    Route::delete('/remove-from-cart/{product}', [CartController::class, 'remove'])->name('remove-from-cart');

    Route::delete('/destroy-cart', [CartController::class, 'destroy'])->name('destroy-cart');

    // Favorite list

    Route::get('/favorite-list', [FavoriteController::class, 'index'])->name('favorite-list');

    Route::get('/add-to-favorite/{product}', [FavoriteController::class, 'add'])->name('add-to-favorite');

    Route::delete('/remove-from-favorite/{product}', [FavoriteController::class, 'remove'])->name('remove-from-favorite');

    Route::delete('/destroy-favorite-list', [FavoriteController::class, 'destroy'])->name('destroy-favorite-list');

    // Recent views

    Route::get('/recent-views', RecentViewsController::class)->name('recent-views');
});
