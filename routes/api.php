<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegistrasiController;
use Illuminate\Auth\Events\Login;


Route::post('/registrasi', [RegistrasiController::class, 'register']);

Route::post('/login', [LoginController::class, 'login']);

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);




foreach (glob(__DIR__ . '/api/*.php') as $file) {
    require $file;
}
