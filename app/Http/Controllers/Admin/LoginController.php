<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Administrador;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres'
        ]);

        $credenciales = [
            'email'    => $request->email,
            'password' => $request->password,
            'activo'   => 1
        ];

        if (Auth::guard('admin')->attempt($credenciales, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $admin = Administrador::where('email', $request->email)->first();

        if ($admin && !$admin->activo) {
            return back()
                ->withErrors(['error' => 'Tu cuenta está desactivada. Contacta al administrador del sistema.'])
                ->withInput($request->only('email'));
        }

        if ($admin) {
            return back()
                ->withErrors(['error' => 'La contraseña es incorrecta. Verifica e intenta nuevamente.'])
                ->withInput($request->only('email'));
        }

        return back()
            ->withErrors(['error' => 'No existe una cuenta con este correo electrónico.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Has cerrado sesión correctamente');
    }
}