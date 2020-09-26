<?php

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

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('categories', CategoryController::class)->names('categories');

Route::resource('products', ProductController::class)->names('products');

Route::resource('orders', OrderController::class)->names('orders');

Route::resource('slides', SlideController::class)->names('slides');