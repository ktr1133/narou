<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [topRankController::class, 'show']);

Route::get('/guide', [GuideController::class, 'show']);

Route::get('/create', [CreateController::class, 'show']);