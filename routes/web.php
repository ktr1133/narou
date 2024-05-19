<?php

use App\Http\Controllers\TopRankController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\CreateController;

use Illuminate\Support\Facades\Route;

Route::get('/', [TopRankController::class, 'show'])->name('topRank.show');

Route::get('/guide', [GuideController::class, 'show'])->name('guide.show');

Route::get('/create', [CreateController::class, 'show'])->name('create.show');

Route::get('/detail', [DetailController::class, 'show'])->name('detail.show');