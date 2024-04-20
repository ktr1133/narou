<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/guide', [GuideController::class, 'show']);

Route::get('/create', function () {
    return view('create');
});