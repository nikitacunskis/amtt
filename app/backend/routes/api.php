<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RamController;

Route::prefix('ram')->group(function () {
    Route::get('/list/{page}', [RamController::class, 'list']);
    Route::get('/view/{id}', [RamController::class, 'showCharacter']);
    Route::get('/search', [RamController::class, 'search']);
});