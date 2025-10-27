<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StringController;


// Route::post('/strings', [StringController::class, 'store'])->middleware('api');
// Route::get('/strings/{string_value}', [StringController::class, 'show']);
Route::get('/strings/filter-by-natural-language', [StringController::class, 'filterByNaturalLanguage']);
Route::get('/b', function () {
    return view('welcome');
});
Route::get('/phpinfo', function () {
    phpinfo();
});



