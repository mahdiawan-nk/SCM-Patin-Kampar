<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KolamMonitoringController;

Route::controller(KolamMonitoringController::class)->group(function () {
    Route::get('/kolam-monitoring', 'index');
    Route::get('/kolam-monitoring/{id}', 'show');
    Route::post('/kolam-monitoring', 'store');
    Route::put('/kolam-monitoring/{id}', 'update');
    Route::delete('/kolam-monitoring/{id}', 'destroy');
    Route::get('/kolam-monitoring/{id}/replicate', 'replicate');
    Route::post('/kolam-monitoring/create/faker', 'createFaker');
    Route::post('/kolam-monitoring/{id}/trash', 'trashed')->name('trash');
    Route::post('/kolam-monitoring/{id}/restore', 'Restore')->name('restore');
});