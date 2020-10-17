<?php

use App\Http\Controllers\Admin\Images\ImageController;
use App\Http\Controllers\Admin\Categories\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Products\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Slides\SlideController;
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
    Route::get('/', function () {
        return \Inertia\Inertia::render('Admin/AdminDashboard');
    })->name('dashboard');

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
});

// Frontend routes

Route::get('/', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('categories', CategoryController::class)->names('categories');

Route::resource('products', ProductController::class)->names('products');

Route::resource('orders', OrderController::class)->names('orders');

Route::resource('slides', SlideController::class)->names('slides');