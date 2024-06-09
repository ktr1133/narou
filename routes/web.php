<?php

use App\Http\Controllers\TopRankController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\DetailController;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DetailPostRequest;

use Illuminate\Support\Facades\Route;

Route::get('/', [TopRankController::class, 'show'])->name('top-rank.show');

Route::get('/guide', [GuideController::class, 'show'])->name('guide.show');

Route::get('/create', [CreateController::class, 'show'])->name('create.show');

Route::get('/create/result', [ResultController::class, 'show'])->name('result.show');

Route::post('/detail', [DetailController::class, 'show'])->name('detail.show');
