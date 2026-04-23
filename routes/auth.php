<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'create'])
                ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});