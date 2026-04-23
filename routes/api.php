<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Bike routes
Route::get('/bikes', [BikeController::class, 'index']);
Route::post('/bikes/{id}/rent', [BikeController::class, 'rent']);

// Accessory routes
Route::get('/accessories', [AccessoryController::class, 'index']);
Route::post('/accessories/order', [AccessoryController::class, 'order']);

// Admin routes (would need authentication middleware in production)
Route::post('/admin/reset', [AdminController::class, 'reset']);