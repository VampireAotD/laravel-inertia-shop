<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\Images\ImageController;
use App\Http\Controllers\Admin\Categories\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Products\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Orders\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Frontend\Cart\CartController;
use App\Http\Controllers\Frontend\Favorite\FavoriteController;
use App\Http\Controllers\Frontend\Home\HomeController;
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
    Route::get('/', [AdminHomeController::class, 'index'])->middleware('permission:see dashboard')->name('dashboard');

    // Users
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');

    Route::patch('/users/{user}', [UserController::class, 'changeRole'])
        ->middleware('permission:change user role')
        ->name('users.change-role');

    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'destroy'])
        ->middleware('permission:see users list|see one user|delete user')
        ->names('users');

    // Categories
    Route::get('categories/search', [AdminCategoryController::class, 'search'])->name('categories.search');

    Route::resource('categories', AdminCategoryController::class)
        ->middleware('permission:see categories list|see one category|create category|edit category|delete category')
        ->names('categories');

    // Products
    Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');

    Route::resource('products', AdminProductController::class)
        ->middleware('permission:see products list|see one product|create product|edit product|delete product')
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
        ->except(['show'])
        ->middleware('permission:see orders list|accept one order|cancel one order|delete order')
        ->names('orders');

    Route::get('/orders/{user}/{date}', [AdminOrderController::class, 'show'])
        ->middleware('permission:see one order')
        ->name('orders.show');
});

// Frontend routes

Route::group(['middleware' => ['favorite-list', 'cart']], function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Profile and API Tokens

    Route::get('user/profile', [\Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::class, 'show'])->name('profile.show');

    Route::get('user/api-tokens', [\Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController::class, 'index'])->name('api-tokens.index');

    // Product

    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::get('/add-to-cart/{product}', [CartController::class, 'add'])->name('add-to-cart');

    Route::get('/remove-from-cart/{product}', [CartController::class, 'remove'])->name('remove-from-cart');

    Route::get('/destroy-cart', [CartController::class, 'destroy'])->name('destroy-cart');

    // Favorite list
    Route::get('/favorite-list', [FavoriteController::class, 'index'])->name('favorite-list');

    Route::get('/add-to-favorite/{product}', [FavoriteController::class, 'add'])->name('add-to-favorite');

    Route::get('/remove-from-favorite/{product}', [FavoriteController::class, 'remove'])->name('remove-from-favorite');

    Route::delete('/destroy-favorite-list', [FavoriteController::class, 'destroy'])->name('destroy-favorite-list');
});
