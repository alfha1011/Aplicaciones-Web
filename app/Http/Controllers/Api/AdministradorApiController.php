<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdministradorApiController extends Controller
{
    private string $imageFolder = 'public/administradores';

    public function index(Request $request): Response
    {
        $administradores = Administrador::all();

        $administradores->transform(function ($admin) {
            return $this->formatearAdmin($admin);
        });

        return response([
            'success' => true,
            'data'    => $administradores,
            'total'   => $administradores->count(),
        ], 200);
    }

    public function show(Request $request, $id): Response
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response([
                'success' => false,
                'message' => 'Administrador no encontrado',
            ], 404);
        }

        return response([
            'success' => true,
            'data'    => $this->formatearAdmin($administrador),
        ], 200);
    }

    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:administradores,email',
            'password' => 'required|string|min:6',
            'telefono' => 'required|string|max:15',
            'rol'      => 'required|in:master,base',
            'imagen'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'    => 'El nombre es obligatorio',
            'nombre.max'         => 'El nombre no puede superar 255 caracteres',
            'apellido.required'  => 'El apellido es obligatorio',
            'email.required'     => 'El email es obligatorio',
            'email.email'        => 'El email no tiene un formato válido',
            'email.unique'       => 'Este email ya está registrado',
            'password.required'  => 'La contraseña es obligatoria',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres',
            'telefono.required'  => 'El teléfono es obligatorio',
            'telefono.max'       => 'El teléfono no puede superar 15 caracteres',
            'rol.required'       => 'El rol es obligatorio',
            'rol.in'             => 'El rol debe ser: master o base',
            'imagen.image'       => 'El archivo debe ser una imagen',
            'imagen.mimes'       => 'La imagen debe ser jpeg, png, jpg o webp',
            'imagen.max'         => 'La imagen no debe superar 2MB',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $administrador = Administrador::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'activo'   => 1,
            'rol'      => $request->rol,
        ]);

        if ($request->hasFile('imagen')) {
            $nombreArchivo = "administradores_{$administrador->id}." . $request->file('imagen')->getClientOriginalExtension();
            $request->file('imagen')->storeAs($this->imageFolder, $nombreArchivo);
            $administrador->imagen = $nombreArchivo;
            $administrador->save();
        }

        return response([
            'success' => true,
            'message' => 'Administrador creado correctamente',
            'data'    => $this->formatearAdmin($administrador),
        ], 201);
    }

    public function update(Request $request, $id): Response
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response([
                'success' => false,
                'message' => 'Administrador no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:administradores,email,' . $id,
            'telefono' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'rol'      => 'required|in:master,base',
            'activo'   => 'nullable|boolean',
            'imagen'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'    => 'El nombre es obligatorio',
            'apellido.required'  => 'El apellido es obligatorio',
            'email.required'     => 'El email es obligatorio',
            'email.email'        => 'El email no tiene un formato válido',
            'email.unique'       => 'Este email ya está registrado por otro administrador',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres',
            'telefono.max'       => 'El teléfono no puede superar 15 caracteres',
            'rol.required'       => 'El rol es obligatorio',
            'rol.in'             => 'El rol debe ser: master o base',
            'imagen.image'       => 'El archivo debe ser una imagen',
            'imagen.mimes'       => 'La imagen debe ser jpeg, png, jpg o webp',
            'imagen.max'         => 'La imagen no debe superar 2MB',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $administrador->nombre   = $request->nombre;
        $administrador->apellido = $request->apellido;
        $administrador->email    = $request->email;
        $administrador->telefono = $request->telefono;
        $administrador->rol      = $request->rol;

        if ($request->filled('password')) {
            $administrador->password = Hash::make($request->password);
        }

        if ($request->has('activo')) {
            $administrador->activo = $request->activo;
        }

        if ($request->hasFile('imagen')) {
            if ($administrador->imagen) {
                Storage::delete($this->imageFolder . '/' . $administrador->imagen);
            }
            $nombreArchivo = "administradores_{$administrador->id}." . $request->file('imagen')->getClientOriginalExtension();
            $request->file('imagen')->storeAs($this->imageFolder, $nombreArchivo);
            $administrador->imagen = $nombreArchivo;
        }

        $administrador->save();

        return response([
            'success' => true,
            'message' => 'Administrador actualizado correctamente',
            'data'    => $this->formatearAdmin($administrador),
        ], 200);
    }
    public function destroy(Request $request, $id): Response
    {
        $administrador = Administrador::find($id);

        if (!$administrador) {
            return response([
                'success' => false,
                'message' => 'Administrador no encontrado',
            ], 404);
        }

        if ($administrador->imagen) {
            Storage::delete($this->imageFolder . '/' . $administrador->imagen);
        }

        $nombre = $administrador->nombre;
        $administrador->delete();

        return response([
            'success' => true,
            'message' => "Administrador \"$nombre\" eliminado correctamente",
        ], 200);
    }

    private function formatearAdmin(Administrador $admin): array
    {
        $data = $admin->toArray();
        unset($data['password']);
        $data['imagen_url'] = $admin->imagen
            ? asset('storage/administradores/' . $admin->imagen)
            : null;
        return $data;
    }
}
