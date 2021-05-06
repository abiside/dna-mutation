<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MutationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/')->group(function () {
    Route::post('mutation', [
        MutationController::class,
        'store'
    ]);
});
