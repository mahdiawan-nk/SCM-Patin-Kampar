<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StockFeedingDrugController;

Route::controller(StockFeedingDrugController::class)->group(function () {
    Route::get('/stock-feeding-drug', 'index');
    Route::get('/stock-feeding-drug/{id}', 'show');
    Route::post('/stock-feeding-drug', 'store');
    Route::put('/stock-feeding-drug/{id}', 'update');
    Route::delete('/stock-feeding-drug/{id}', 'destroy');
    Route::get('/stock-feeding-drug/{id}/replicate', 'replicate');
    Route::post('/stock-feeding-drug/create/faker', 'createFaker');
    Route::post('/stock-feeding-drug/{id}/trash', 'trashed')->name('trash');
    Route::post('/stock-feeding-drug/{id}/restore', 'Restore')->name('restore');
});