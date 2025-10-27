<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StringController;

Route::get('/', function () {
    return view('welcome');
});



// Route::post('/strings', [StringController::class, 'store'])->middleware('api');
// Route::get('/strings/{string_value}', [StringController::class, 'show']);
Route::get('/strings/filter-by-natural-language', [StringController::class, 'filterByNaturalLanguage']);
Route::get('/b', function () {
    return view('welcome');
});
Route::get('/strings', [StringController::class, 'getStrings']);
Route::get('/strings/{string_value}', [StringController::class, 'show'])
    ->where('string_value', '.*');


