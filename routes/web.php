<?php

use Illuminate\Support\Facades\Route;

// Public Home Route
Route::get('/', function () {
    return view('welcome', [
        'latestProducts' => collect(),
        'services' => collect(),
    ]);
});
