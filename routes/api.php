<?php

use App\Http\Controllers\API\Frontend\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'throttle:1000,1'])->get('/user', function (Request $request) {
    return $request->user();
});

// ElasticSearch

Route::post('/search', SearchController::class)->name('search');

