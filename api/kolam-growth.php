<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamGrowthController;

Route::controller(KolamGrowthController::class)->group(function () {
    Route::get('/kolam-growth', 'index');
    Route::get('/kolam-growth/{id}', 'show');
    Route::post('/kolam-growth', 'store');
    Route::put('/kolam-growth/{id}', 'update');
    Route::delete('/kolam-growth/{id}', 'destroy');
    Route::get('/kolam-growth/{id}/replicate', 'replicate');
    Route::post('/kolam-growth/create/faker', 'createFaker');
    Route::post('/kolam-growth/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-growth/{id}/restore', 'Restore')->name('restore');
});