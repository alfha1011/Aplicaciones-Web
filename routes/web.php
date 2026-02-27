<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Administrador;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\Admin\LoginController;

// ============================================
// RUTA RAÍZ
// ============================================

Route::get('/', function () {
    return redirect('/login');
});

// ============================================
// LOGIN
// ============================================

Route::view('/login', 'auth.login')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.procesar');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// ============================================
// GOOGLE SOCIALITE (ADMIN)
// ============================================

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('auth/google/callback', function () {
    try {

        $googleUser = Socialite::driver('google')->user();

        $admin = Administrador::where('email', $googleUser->getEmail())->first();

        if ($admin) {

            if ($admin->activo == 1) {

                Auth::guard('admin')->login($admin);

                return redirect('/dashboard');
            }

            return redirect('/login')
                ->withErrors(['error' => 'Cuenta de administrador inactiva.']);
        }

        return redirect('/login')
            ->withErrors(['error' => 'No tienes permisos de administrador.']);

    } catch (Exception $e) {
        return redirect('/login')
            ->withErrors(['error' => 'Error al autenticar con Google']);
    }

})->name('google.callback');


// ============================================
// RUTAS PROTEGIDAS (SOLO ADMIN)
// ============================================

Route::middleware(['auth:admin'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

    // ================= ADMINISTRADORES =================
    Route::get('/admins/listado', [AdministradorController::class, 'listado'])->name('admins.listado');
    Route::get('/admins/registro', [AdministradorController::class, 'registro'])->name('admins.registro');
    Route::post('/admins/guardar', [AdministradorController::class, 'guardar'])->name('admins.guardar');
    Route::get('/admins/{id}/editar', [AdministradorController::class, 'editar'])->name('admins.editar');
    Route::put('/admins/actualizar/{id}', [AdministradorController::class, 'actualizar'])->name('admins.actualizar');
    Route::delete('/admins/{id}', [AdministradorController::class, 'eliminar'])->name('admins.eliminar');


    // ================= CLIENTES =================
    Route::get('/clientes/listado', [ClienteController::class, 'listado'])->name('clientes.listado');
    Route::get('/clientes/registro', [ClienteController::class, 'registro'])->name('clientes.registro');
    Route::post('/clientes/guardar', [ClienteController::class, 'guardar'])->name('clientes.guardar');
    Route::get('/clientes/{id}/editar', [ClienteController::class, 'editar'])->name('clientes.editar');
    Route::put('/clientes/actualizar/{id}', [ClienteController::class, 'actualizar'])->name('clientes.actualizar');
    Route::delete('/clientes/{id}', [ClienteController::class, 'eliminar'])->name('clientes.eliminar');


    // ================= CANCHAS =================
    Route::get('/canchas', [CanchaController::class, 'listado'])->name('canchas.listado');
    Route::get('/canchas/deshabilitadas', [CanchaController::class, 'deshabilitadas'])->name('canchas.deshabilitadas');
    Route::patch('/canchas/{id}/deshabilitar', [CanchaController::class, 'deshabilitar'])->name('canchas.deshabilitar');
    Route::patch('/canchas/{id}/habilitar', [CanchaController::class, 'habilitar'])->name('canchas.habilitar');
    Route::get('/canchas/registro', [CanchaController::class, 'registro'])->name('canchas.registro');
    Route::post('/canchas', [CanchaController::class, 'guardar'])->name('canchas.guardar');
    Route::get('/canchas/{id}/editar', [CanchaController::class, 'editar'])->name('canchas.editar');
    Route::put('/canchas/{id}', [CanchaController::class, 'actualizar'])->name('canchas.actualizar');
    Route::delete('/canchas/{id}', [CanchaController::class, 'eliminar'])->name('canchas.eliminar');


    // TEST ENV
    Route::get('/test-env', function () {
        dd(env('GEOCODING_API_KEY'));
    });

});
