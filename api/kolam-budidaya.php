<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamBudidayaController;

Route::controller(KolamBudidayaController::class)->group(function () {
    Route::get('/kolam-budidaya', 'index');
    Route::get('/kolam-budidaya/{id}', 'show');
    Route::post('/kolam-budidaya', 'store');
    Route::put('/kolam-budidaya/{id}', 'update');
    Route::delete('/kolam-budidaya/{id}', 'destroy');
    Route::get('/kolam-budidaya/{id}/replicate', 'replicate');
    Route::post('/kolam-budidaya/create/faker', 'createFaker');
    Route::post('/kolam-budidaya/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-budidaya/{id}/restore', 'Restore')->name('restore');
});
