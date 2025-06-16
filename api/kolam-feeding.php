<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamFeedingController;

Route::controller(KolamFeedingController::class)->group(function () {
    Route::get('/kolam-feeding', 'index');
    Route::get('/kolam-feeding/{id}', 'show');
    Route::post('/kolam-feeding', 'store');
    Route::put('/kolam-feeding/{id}', 'update');
    Route::delete('/kolam-feeding/{id}', 'destroy');
    Route::get('/kolam-feeding/{id}/replicate', 'replicate');
    Route::post('/kolam-feeding/create/faker', 'createFaker');
    Route::post('/kolam-feeding/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-feeding/{id}/restore', 'Restore')->name('restore');
});