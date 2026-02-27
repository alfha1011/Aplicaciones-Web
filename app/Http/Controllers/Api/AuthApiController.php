<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    // INICIAR SESIÓN
    public function login(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El email es obligatorio',
            'email.email'       => 'El email no tiene formato válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Buscar el administrador por email
        $administrador = Administrador::where('email', $request->email)->first();

        // Verificar si existe y si la contraseña es correcta
        if (!$administrador || !Hash::check($request->password, $administrador->password)) {
            return response([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        // Verificar que esté activo
        if (!$administrador->activo) {
            return response([
                'success' => false,
                'message' => 'Tu cuenta está desactivada',
            ], 403);
        }

        // Eliminar tokens anteriores
        $administrador->tokens()->delete();

        // Crear el token
        $token = $administrador->createToken('admin-token')->plainTextToken;

        return response([
            'success' => true,
            'message' => 'Sesión iniciada correctamente',
            'token'   => $token,
            'data'    => [
                'id'       => $administrador->id,
                'nombre'   => $administrador->nombre,
                'apellido' => $administrador->apellido,
                'email'    => $administrador->email,
                'rol'      => $administrador->rol,
            ],
        ], 200);
    }

    // CERRAR SESIÓN
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'success' => true,
            'message' => 'Sesión cerrada correctamente',
        ], 200);
    }

    // VER MI PERFIL
    public function me(Request $request): Response
    {
        $admin = $request->user();

        return response([
            'success' => true,
            'data'    => [
                'id'       => $admin->id,
                'nombre'   => $admin->nombre,
                'apellido' => $admin->apellido,
                'email'    => $admin->email,
                'rol'      => $admin->rol,
            ],
        ], 200);
    }
}