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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum', 'authority', 'user-data']], function () {

    // Dashboard
    Route::get('/', [AdminHomeController::class, 'index'])->name('dashboard');

    // Users
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');

    Route::patch('/users/{user}', [UserController::class, 'changeRole'])->name('users.change-role');

    Route::resource('users', UserController::class)->only(['index', 'show', 'destroy'])->names('users');

    // Categories
    Route::get('categories/search', [AdminCategoryController::class, 'search'])->name('categories.search');

    Route::resource('categories', AdminCategoryController::class)->names('categories');

    // Products
    Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');

    Route::resource('products', AdminProductController::class)->names('products');

    // Images
    Route::get('/images/{image}', [ImageController::class, 'updateImage'])->name('images.update-main-image');

    Route::delete('/images/{image}', [ImageController::class, 'destroyImage'])->name('images.destroy-image');

    // Orders

    Route::resource('orders', AdminOrderController::class)->except(['show'])->names('orders');

    Route::get('/orders/{user}/{date}', [AdminOrderController::class, 'show'])->name('orders.show');
});

// Frontend routes

Route::group(['middleware' => 'favorite-list', 'cart'], function () {

    // Home

    Route::get('/', [HomeController::class, 'index'])->middleware(['favorite-list', 'cart'])->name('home');

    // Cart

    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::get('/add-to-cart/{product}', [CartController::class, 'add'])->name('add-to-cart');

    Route::get('/remove-from-cart/{product}', [CartController::class, 'remove'])->name('remove-from-cart');

    Route::get('/destroy-cart', [CartController::class, 'destroy'])->name('destroy-cart');

    // Favorite list

    Route::get('/favorite-list', [FavoriteController::class, 'index'])->name('favorite-list');

    Route::get('/add-to-favorite/{product}', [FavoriteController::class, 'add'])->name('add-to-favorite');

    Route::delete('/remove-from-favorite/{product}', [FavoriteController::class, 'remove'])->name('remove-from-favorite');

    Route::delete('/destroy-favorite-list', [FavoriteController::class, 'destroy'])->name('destroy-favorite-list');
});
