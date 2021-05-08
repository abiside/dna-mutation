<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::post('/auth', AuthController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/mutation', [
        MutationController::class,
        'store'
    ]);
});
