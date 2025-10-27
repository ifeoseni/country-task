<?php

use Illuminate\Http\Request;
use App\Http\Controllers\StringController;
use App\Http\Controllers\Api\CountriesController;
use Illuminate\Support\Facades\Route;

// Route::prefix('countries')->group(function () {
//     Route::post('refresh', [CountriesController::class, 'refresh']);
//     Route::get('', [CountriesController::class, 'index']);
//     Route::get('image', [CountriesController::class, 'image']);
//     Route::get('status', [CountriesController::class, 'status']); // Moved inside prefix
//     Route::get('{name}', [CountriesController::class, 'show']);
//     Route::delete('{name}', [CountriesController::class, 'destroy']);
// });

Route::prefix('countries')->group(function () {
    Route::post('refresh', [CountriesController::class, 'refresh']);
    Route::get('image', [CountriesController::class, 'image']);
    Route::get('status', [CountriesController::class, 'status']); // ← MUST BE HERE
    Route::get('', [CountriesController::class, 'index']);
    Route::get('{name}', [CountriesController::class, 'show']);
    Route::delete('{name}', [CountriesController::class, 'destroy']);
});