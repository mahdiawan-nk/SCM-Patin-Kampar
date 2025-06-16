<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PembudidayaController;


Route::controller(PembudidayaController::class)->group(function () {
    Route::get('/pembudidaya', 'index');
    Route::get('/pembudidaya/{id}', 'show');
    Route::post('/pembudidaya', 'store');
    Route::put('/pembudidaya/{id}', 'update');
    Route::delete('/pembudidaya/{id}', 'destroy');
    Route::get('/pembudidaya/{id}/replicate', 'replicate');
    Route::post('/pembudidaya/create/faker', 'createFaker');
    Route::post('/pembudidaya/{id}/trash', 'trash')->name('trash');
    Route::post('/pembudidaya/{id}/restore', 'Restore')->name('restore');
    Route::get('/pembudidaya/column/data', 'columnInput')->name('column');
});
