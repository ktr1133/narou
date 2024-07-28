<?php

use App\Http\Controllers\TopRankController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\ResultController;
use App\Http\Actions\ResultWGetAction;
use App\Http\Controllers\DetailController;

use Illuminate\Support\Facades\Route;

Route::get('/', [TopRankController::class, 'show'])->name('top-rank.show');

Route::get('/guide', [GuideController::class, 'show'])->name('guide.show');

Route::get('/create', [CreateController::class, 'show'])->name('create.show');

Route::get('/create/result-by-title', [ResultController::class, 'show'])->name('result.show');

Route::get('/create/result-by-writer', ResultWGetAction::class)->name('result-w');

Route::post('/detail', [DetailController::class, 'show'])->name('detail.show');
