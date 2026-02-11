<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;

// ============================================
// RUTAS DE AUTENTICACIÓN
// ============================================

// Ruta raíz - redirige según si está autenticado o no
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login'); 
});

// Mostrar formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

// Procesar login (POST)
Route::post('/inicio', [LoginController::class, 'login'])->name('inicio');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
        
        // Buscar usuario por email o google_id
        $user = User::where('email', $googleUser->getEmail())->first();
        
        if (!$user) {
            // Crear nuevo usuario
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null, // No necesita password para login con Google
            ]);
        } else {
            // Actualizar google_id si no lo tiene
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }
        
        // Iniciar sesión
        Auth::login($user);
        
        // Redirigir al dashboard
        return redirect('/dashboard');
        
    } catch (Exception $e) {
        return redirect('/login')->with('error', 'Error al autenticar con Google');
    }
})->name('google.callback');

// ============================================
// DASHBOARD
// ============================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// ============================================
// RUTAS DE ADMINISTRADORES (Protegidas)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/admins/listado', [AdministradorController::class, 'listado'])->name('admins.listado');
    Route::get('/admins/registro', [AdministradorController::class, 'registro'])->name('admins.registro');
    Route::post('/admins/guardar', [AdministradorController::class, 'guardar'])->name('admins.guardar');
    Route::get('/admins/{id}/editar', [AdministradorController::class, 'editar'])->name('admins.editar');
    Route::put('/admins/actualizar/{id}', [AdministradorController::class, 'actualizar'])->name('admins.actualizar');
    Route::delete('/admins/{id}', [AdministradorController::class, 'eliminar'])->name('admins.eliminar');
});

// ============================================
// RUTAS DE CLIENTES (Protegidas)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/clientes/listado', [ClienteController::class, 'listado'])->name('clientes.listado');
    Route::get('/clientes/registro', [ClienteController::class, 'registro'])->name('clientes.registro');
    Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');
    Route::get('/clientes/{id}/editar', [ClienteController::class, 'editar'])->name('clientes.editar');
    Route::put('/clientes/actualizar/{id}', [ClienteController::class, 'actualizar'])->name('clientes.actualizar');
    Route::delete('/clientes/{id}', [ClienteController::class, 'eliminar'])->name('clientes.eliminar');
});

// ============================================
// RUTAS DE CANCHAS (Protegidas)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/canchas', [CanchaController::class, 'listado'])->name('canchas.listado');
    Route::get('/canchas/deshabilitadas', [CanchaController::class, 'deshabilitadas'])->name('canchas.deshabilitadas');
    Route::patch('/canchas/{id}/deshabilitar', [CanchaController::class, 'deshabilitar'])->name('canchas.deshabilitar');
    Route::patch('/canchas/{id}/habilitar', [CanchaController::class, 'habilitar'])->name('canchas.habilitar');
    Route::get('/canchas/registro', [CanchaController::class, 'registro'])->name('canchas.registro');
    Route::post('/canchas', [CanchaController::class, 'guardar'])->name('canchas.guardar');
    Route::get('/canchas/{id}/editar', [CanchaController::class, 'editar'])->name('canchas.editar');
    Route::put('/canchas/{id}', [CanchaController::class, 'actualizar'])->name('canchas.actualizar');
    Route::delete('/canchas/{id}', [CanchaController::class, 'eliminar'])->name('canchas.eliminar');
});

Route::get('/test-env', function () {
    dd(env('GEOCODING_API_KEY'));
})->middleware('auth');