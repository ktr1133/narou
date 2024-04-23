<?php

use App\Http\Controllers\TopRankController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\CreateController;

use Illuminate\Support\Facades\Route;

Route::get('/', [TopRankController::class, 'show']);

Route::get('/guide', [GuideController::class, 'show']);

Route::get('/create', [CreateController::class, 'show']);