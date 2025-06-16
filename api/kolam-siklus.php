<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamSiklusController;

Route::controller(KolamSiklusController::class)->group(function () {
    Route::get('/kolam-siklus', 'index');
    Route::get('/kolam-siklus/{id}', 'show');
    Route::post('/kolam-siklus', 'store');
    Route::put('/kolam-siklus/{id}', 'update');
    Route::delete('/kolam-siklus/{id}', 'destroy');
    Route::get('/kolam-siklus/{id}/replicate', 'replicate');
    Route::post('/kolam-siklus/create/faker', 'createFaker');
    Route::post('/kolam-siklus/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-siklus/{id}/restore', 'Restore')->name('restore');
});