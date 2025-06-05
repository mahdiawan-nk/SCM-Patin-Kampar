<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamTreatmentController;

Route::controller(KolamTreatmentController::class)->group(function () {
    Route::get('/kolam-treatment', 'index');
    Route::get('/kolam-treatment/{id}', 'show');
    Route::post('/kolam-treatment', 'store');
    Route::put('/kolam-treatment/{id}', 'update');
    Route::delete('/kolam-treatment/{id}', 'destroy');
    Route::get('/kolam-treatment/{id}/replicate', 'replicate');
    Route::post('/kolam-treatment/create/faker', 'createFaker');
    Route::post('/kolam-treatment/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-treatment/{id}/restore', 'Restore')->name('restore');
});