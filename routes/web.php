<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/guide', function () {
    return view('guide');
});

Route::get('/create', function () {
    return view('create');
});