<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ClienteApiController extends Controller
{
    private string $imageFolder = 'public/clientes';

    public function index(Request $request): Response
    {
        $clientes = Cliente::all();

        $clientes->transform(function ($cliente) {
            return $this->formatearCliente($cliente);
        });

        return response([
            'success' => true,
            'data'    => $clientes,
            'total'   => $clientes->count(),
        ], 200);
    }
    public function show(Request $request, $id): Response
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response([
                'success' => false,
                'message' => 'Cliente no encontrado',
            ], 404);
        }

        return response([
            'success' => true,
            'data'    => $this->formatearCliente($cliente),
        ], 200);
    }

    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'email'     => 'required|email|unique:clientes,email',
            'telefono'  => 'required|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'latitud'   => 'nullable|numeric|between:-90,90',
            'longitud'  => 'nullable|numeric|between:-180,180',
            'imagen'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'    => 'El nombre es obligatorio',
            'nombre.max'         => 'El nombre no puede superar 255 caracteres',
            'apellido.required'  => 'El apellido es obligatorio',
            'apellido.max'       => 'El apellido no puede superar 255 caracteres',
            'email.required'     => 'El email es obligatorio',
            'email.email'        => 'El email no tiene un formato válido',
            'email.unique'       => 'Este email ya está registrado',
            'telefono.required'  => 'El teléfono es obligatorio',
            'telefono.max'       => 'El teléfono no puede superar 15 caracteres',
            'latitud.between'    => 'La latitud debe estar entre -90 y 90',
            'longitud.between'   => 'La longitud debe estar entre -180 y 180',
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

        $cliente = Cliente::create([
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'email'     => $request->email,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
            'latitud'   => $request->latitud,
            'longitud'  => $request->longitud,
        ]);

        if ($request->hasFile('imagen')) {
            $nombreArchivo = "clientes_{$cliente->id}." . $request->file('imagen')->getClientOriginalExtension();
            $request->file('imagen')->storeAs($this->imageFolder, $nombreArchivo);
            $cliente->imagen = $nombreArchivo;
            $cliente->save();
        }

        return response([
            'success' => true,
            'message' => 'Cliente creado correctamente',
            'data'    => $this->formatearCliente($cliente),
        ], 201);
    }
    public function update(Request $request, $id): Response
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response([
                'success' => false,
                'message' => 'Cliente no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'email'     => 'required|email|unique:clientes,email,' . $id,
            'telefono'  => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'latitud'   => 'nullable|numeric|between:-90,90',
            'longitud'  => 'nullable|numeric|between:-180,180',
            'imagen'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'    => 'El nombre es obligatorio',
            'apellido.required'  => 'El apellido es obligatorio',
            'email.required'     => 'El email es obligatorio',
            'email.email'        => 'El email no tiene un formato válido',
            'email.unique'       => 'Este email ya está registrado por otro cliente',
            'telefono.max'       => 'El teléfono no puede superar 15 caracteres',
            'latitud.between'    => 'La latitud debe estar entre -90 y 90',
            'longitud.between'   => 'La longitud debe estar entre -180 y 180',
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

        $cliente->nombre    = $request->nombre;
        $cliente->apellido  = $request->apellido;
        $cliente->email     = $request->email;
        $cliente->telefono  = $request->telefono;
        $cliente->direccion = $request->direccion;

        if ($request->filled('latitud') && $request->filled('longitud')) {
            $cliente->latitud  = $request->latitud;
            $cliente->longitud = $request->longitud;
        }

        if ($request->hasFile('imagen')) {
            if ($cliente->imagen) {
                Storage::delete($this->imageFolder . '/' . $cliente->imagen);
            }
            $nombreArchivo = "clientes_{$cliente->id}." . $request->file('imagen')->getClientOriginalExtension();
            $request->file('imagen')->storeAs($this->imageFolder, $nombreArchivo);
            $cliente->imagen = $nombreArchivo;
        }

        $cliente->save();

        return response([
            'success' => true,
            'message' => 'Cliente actualizado correctamente',
            'data'    => $this->formatearCliente($cliente),
        ], 200);
    }

    public function destroy(Request $request, $id): Response
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response([
                'success' => false,
                'message' => 'Cliente no encontrado',
            ], 404);
        }

        if ($cliente->imagen) {
            Storage::delete($this->imageFolder . '/' . $cliente->imagen);
        }

        $nombreCliente = $cliente->nombre;
        $cliente->delete();

        return response([
            'success' => true,
            'message' => "Cliente \"$nombreCliente\" eliminado correctamente",
        ], 200);
    }

    private function formatearCliente(Cliente $cliente): array
    {
        $data = $cliente->toArray();
        $data['imagen_url'] = $cliente->imagen
            ? asset('storage/clientes/' . $cliente->imagen)
            : null;
        return $data;
    }
}