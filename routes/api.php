<?php

use App\Http\Controllers\IzenEmateaController;
use App\Http\Controllers\JardueraController;
use App\Http\Controllers\KategoriaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Ruta guztiek /api aurrizkia dute automatikoki.

Route::get('/ping', function () {
    return ['mezua' => 'API martxan dago'];
});

Route::apiResource('users', UserController::class);
Route::apiResource('kategoriak', KategoriaController::class);
Route::apiResource('jarduerak', JardueraController::class);

Route::get('/users/{id}/jarduerak', [UserController::class, 'jarduerak']);
Route::post('/jarduerak/{id}/izen-emateak', [IzenEmateaController::class, 'store']);
Route::delete('/jarduerak/{id}/izen-emateak/{userId}', [IzenEmateaController::class, 'destroy']);
