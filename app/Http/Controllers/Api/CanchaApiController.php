<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CanchaApiController extends Controller
{
    private string $imageFolder = 'public/canchas';

    public function index(Request $request): Response
    {
        if ($request->query('todas') == 1) {
            $canchas = Cancha::all();
        } else {
            $canchas = Cancha::where('activo', 1)->get();
        }

        $canchas->transform(function ($cancha) {
            return $this->formatearCancha($cancha);
        });

        return response([
            'success' => true,
            'data'    => $canchas,
            'total'   => $canchas->count(),
        ], 200);
    }
    public function show(Request $request, $id): Response
    {
        $cancha = Cancha::find($id);

        if (!$cancha) {
            return response([
                'success' => false,
                'message' => 'Cancha no encontrada',
            ], 404);
        }

        return response([
            'success' => true,
            'data'    => $this->formatearCancha($cancha),
        ], 200);
    }
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:255',
            'tipo'        => 'required|string|max:100',
            'precio_hora' => 'required|numeric|min:0',
            'estado'      => 'required|in:disponible,ocupada,mantenimiento',
            'descripcion' => 'nullable|string|max:1000',
            'latitud'     => 'nullable|numeric|between:-90,90',
            'longitud'    => 'nullable|numeric|between:-180,180',
            'activo'      => 'nullable|boolean',
            'imagen1'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen2'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen3'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'      => 'El nombre de la cancha es obligatorio',
            'nombre.max'           => 'El nombre no puede superar 255 caracteres',
            'tipo.required'        => 'El tipo de cancha es obligatorio',
            'tipo.max'             => 'El tipo no puede superar 100 caracteres',
            'precio_hora.required' => 'El precio por hora es obligatorio',
            'precio_hora.numeric'  => 'El precio debe ser un número',
            'precio_hora.min'      => 'El precio debe ser mayor o igual a 0',
            'estado.required'      => 'El estado es obligatorio',
            'estado.in'            => 'El estado debe ser: disponible, ocupada o mantenimiento',
            'latitud.between'      => 'La latitud debe estar entre -90 y 90',
            'longitud.between'     => 'La longitud debe estar entre -180 y 180',
            'imagen1.image'        => 'El archivo imagen1 debe ser una imagen',
            'imagen1.mimes'        => 'imagen1 debe ser jpeg, png, jpg o webp',
            'imagen1.max'          => 'imagen1 no debe superar 2MB',
            'imagen2.image'        => 'El archivo imagen2 debe ser una imagen',
            'imagen2.mimes'        => 'imagen2 debe ser jpeg, png, jpg o webp',
            'imagen2.max'          => 'imagen2 no debe superar 2MB',
            'imagen3.image'        => 'El archivo imagen3 debe ser una imagen',
            'imagen3.mimes'        => 'imagen3 debe ser jpeg, png, jpg o webp',
            'imagen3.max'          => 'imagen3 no debe superar 2MB',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $cancha = Cancha::create([
            'nombre'      => $request->nombre,
            'tipo'        => $request->tipo,
            'precio_hora' => $request->precio_hora,
            'estado'      => $request->estado,
            'descripcion' => $request->descripcion,
            'activo'      => $request->activo ?? true,
        ]);

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $nombreArchivo = "canchas_{$cancha->id}_{$campo}." . $request->file($campo)->getClientOriginalExtension();
                $request->file($campo)->storeAs($this->imageFolder, $nombreArchivo);
                $cancha->$campo = $nombreArchivo;
            }
        }

        $cancha->save();

        return response([
            'success' => true,
            'message' => 'Cancha creada correctamente',
            'data'    => $this->formatearCancha($cancha),
        ], 201);
    }

    public function update(Request $request, $id): Response
    {
        $cancha = Cancha::find($id);

        if (!$cancha) {
            return response([
                'success' => false,
                'message' => 'Cancha no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:255',
            'tipo'        => 'required|string|max:100',
            'precio_hora' => 'required|numeric|min:0',
            'estado'      => 'required|in:disponible,ocupada,mantenimiento',
            'descripcion' => 'nullable|string|max:1000',
            'latitud'     => 'nullable|numeric|between:-90,90',
            'longitud'    => 'nullable|numeric|between:-180,180',
            'activo'      => 'nullable|boolean',
            'imagen1'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen2'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'imagen3'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nombre.required'      => 'El nombre de la cancha es obligatorio',
            'nombre.max'           => 'El nombre no puede superar 255 caracteres',
            'tipo.required'        => 'El tipo de cancha es obligatorio',
            'precio_hora.required' => 'El precio por hora es obligatorio',
            'precio_hora.numeric'  => 'El precio debe ser un número',
            'precio_hora.min'      => 'El precio debe ser mayor o igual a 0',
            'estado.required'      => 'El estado es obligatorio',
            'estado.in'            => 'El estado debe ser: disponible, ocupada o mantenimiento',
            'latitud.between'      => 'La latitud debe estar entre -90 y 90',
            'longitud.between'     => 'La longitud debe estar entre -180 y 180',
            'imagen1.image'        => 'El archivo imagen1 debe ser una imagen',
            'imagen1.mimes'        => 'imagen1 debe ser jpeg, png, jpg o webp',
            'imagen1.max'          => 'imagen1 no debe superar 2MB',
            'imagen2.image'        => 'El archivo imagen2 debe ser una imagen',
            'imagen2.mimes'        => 'imagen2 debe ser jpeg, png, jpg o webp',
            'imagen2.max'          => 'imagen2 no debe superar 2MB',
            'imagen3.image'        => 'El archivo imagen3 debe ser una imagen',
            'imagen3.mimes'        => 'imagen3 debe ser jpeg, png, jpg o webp',
            'imagen3.max'          => 'imagen3 no debe superar 2MB',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $cancha->nombre      = $request->nombre;
        $cancha->tipo        = $request->tipo;
        $cancha->precio_hora = $request->precio_hora;
        $cancha->estado      = $request->estado;
        $cancha->descripcion = $request->descripcion;

        if ($request->has('activo')) {
            $cancha->activo = $request->activo;
        }

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                if ($cancha->$campo) {
                    Storage::delete($this->imageFolder . '/' . $cancha->$campo);
                }
                $nombreArchivo = "canchas_{$cancha->id}_{$campo}." . $request->file($campo)->getClientOriginalExtension();
                $request->file($campo)->storeAs($this->imageFolder, $nombreArchivo);
                $cancha->$campo = $nombreArchivo;
            }
        }

        $cancha->save();

        return response([
            'success' => true,
            'message' => 'Cancha actualizada correctamente',
            'data'    => $this->formatearCancha($cancha),
        ], 200);
    }

    public function destroy(Request $request, $id): Response
    {
        $cancha = Cancha::find($id);

        if (!$cancha) {
            return response([
                'success' => false,
                'message' => 'Cancha no encontrada',
            ], 404);
        }

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($cancha->$campo) {
                Storage::delete($this->imageFolder . '/' . $cancha->$campo);
            }
        }

        $nombreCancha = $cancha->nombre;
        $cancha->delete();

        return response([
            'success' => true,
            'message' => "Cancha \"$nombreCancha\" eliminada correctamente",
        ], 200);
    }
    private function formatearCancha(Cancha $cancha): array
    {
        $data = $cancha->toArray();

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            $data["{$campo}_url"] = $cancha->$campo
                ? asset('storage/canchas/' . $cancha->$campo)
                : null;
        }

        return $data;
    }
}