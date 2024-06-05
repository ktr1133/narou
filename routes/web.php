<?php

use App\Http\Controllers\TopRankController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\DetailController;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DetailPostRequest;

use Illuminate\Support\Facades\Route;

Route::get('/', [TopRankController::class, 'show'])->name('topRank.show');

Route::get('/guide', [GuideController::class, 'show'])->name('guide.show');

Route::get('/create', [CreateController::class, 'show'])->name('create.show');

Route::post('/create/result', [ResultController::class, 'show'])
    ->name('result.show')
    ->middleware(CreatePostRequest::class);

Route::get('/create/detail', [DetailController::class, 'show'])
    ->name('detail.show')
    ->middleware(DetailPostRequest::class);