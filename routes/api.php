<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('/auth', AuthController::class);

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::post('/mutation', MutationController::class);
        Route::get('/stats', StatController::class);
    });
});
