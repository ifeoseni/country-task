<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StringController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    phpinfo();
});



