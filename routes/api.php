<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdministradorApiController;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\CanchaApiController;
use App\Http\Controllers\Api\AuthApiController; 

// ==========================================
// RUTAS PÚBLICAS (no necesitan token)
// ==========================================
Route::post('/login',  [AuthApiController::class, 'login']);

// ==========================================
// RUTAS PRIVADAS (necesitan token)
// ==========================================
Route::middleware('auth:admin-api')->group(function () {

    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me',      [AuthApiController::class, 'me']);

    // Administradores
    Route::prefix('administradores')->group(function () {
        Route::get('/',        [AdministradorApiController::class, 'index']);
        Route::get('/{id}',    [AdministradorApiController::class, 'show']);
        Route::post('/',       [AdministradorApiController::class, 'store']);
        Route::put('/{id}',    [AdministradorApiController::class, 'update']);
        Route::delete('/{id}', [AdministradorApiController::class, 'destroy']);
    });

    // Clientes
    Route::prefix('clientes')->group(function () {
        Route::get('/',        [ClienteApiController::class, 'index']);
        Route::get('/{id}',    [ClienteApiController::class, 'show']);
        Route::post('/',       [ClienteApiController::class, 'store']);
        Route::put('/{id}',    [ClienteApiController::class, 'update']);
        Route::delete('/{id}', [ClienteApiController::class, 'destroy']);
    });

    // Canchas
    Route::prefix('canchas')->group(function () {
        Route::get('/',        [CanchaApiController::class, 'index']);
        Route::get('/{id}',    [CanchaApiController::class, 'show']);
        Route::post('/',       [CanchaApiController::class, 'store']);
        Route::put('/{id}',    [CanchaApiController::class, 'update']);
        Route::delete('/{id}', [CanchaApiController::class, 'destroy']);
    });

});