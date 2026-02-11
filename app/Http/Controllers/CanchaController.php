<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use Illuminate\Http\Request;

class CanchaController extends Controller
{
    /**
     * LISTADO - Mostrar solo canchas HABILITADAS (activo = 1)
     */
    public function listado()
    {
        // Solo muestra canchas activas (activo = 1)
        $canchas = Cancha::where('activo', 1)->get();
        
        return view('canchas.listado', compact('canchas'));
    }

    /**
     * LISTADO DE DESHABILITADAS - Mostrar solo canchas DESHABILITADAS (activo = 0)
     */
    public function deshabilitadas()
    {
        // Solo muestra canchas deshabilitadas (activo = 0)
        $canchas = Cancha::where('activo', 0)->get();
        
        return view('canchas.deshabilitadas', compact('canchas'));
    }

    /**
     * DESHABILITAR - Cambia el estado de activo a inactivo (1 → 0)
     */
    public function deshabilitar($id)
    {
        $cancha = Cancha::findOrFail($id);
        
        // Cambia activo a 0 (deshabilitado)
        $cancha->activo = 0;
        $cancha->save();
        
        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha "' . $cancha->nombre . '" deshabilitada correctamente.');
    }

    /**
     * HABILITAR - Cambia el estado de inactivo a activo (0 → 1)
     */
    public function habilitar($id)
    {
        $cancha = Cancha::findOrFail($id);
        
        // Cambia activo a 1 (habilitado)
        $cancha->activo = 1;
        $cancha->save();
        
        return redirect()->route('canchas.deshabilitadas')
                        ->with('success', 'Cancha "' . $cancha->nombre . '" habilitada correctamente.');
    }

    /**
     * REGISTRO - Mostrar formulario para crear nueva cancha
     */
    public function registro()
    {
        return view('canchas.registro');
    }

    /**
     * GUARDAR - Guardar nueva cancha
     */
    public function guardar(Request $request)
    {
        // Validar datos con mensajes en español
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'precio_hora' => 'required|numeric|min:0',
            'estado' => 'required|in:disponible,ocupada,mantenimiento',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre de la cancha es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'tipo.required' => 'El tipo de cancha es obligatorio.',
            'tipo.max' => 'El tipo no puede tener más de 100 caracteres.',
            'precio_hora.required' => 'El precio por hora es obligatorio.',
            'precio_hora.numeric' => 'El precio debe ser un número.',
            'precio_hora.min' => 'El precio debe ser mayor o igual a 0.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser: disponible, ocupada o mantenimiento.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ]);

        // Crear la cancha (por defecto activo = 1)
        Cancha::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'precio_hora' => $request->precio_hora,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'activo' => 1,  // Habilitada por defecto
        ]);

        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha creada exitosamente.');
    }

    /**
     * EDITAR - Mostrar formulario de edición
     */
    public function editar($id)
    {
        // Buscar la cancha por ID
        $cancha = Cancha::findOrFail($id);
        
        // Retornar vista con los datos de la cancha
        return view('canchas.editar', compact('cancha'));
    }

    /**
     * ACTUALIZAR - Actualizar los datos de la cancha
     */
    public function actualizar(Request $request, $id)
    {
        // Buscar la cancha
        $cancha = Cancha::findOrFail($id);

        // Validar datos con mensajes en español
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'precio_hora' => 'required|numeric|min:0',
            'estado' => 'required|in:disponible,ocupada,mantenimiento',
            'descripcion' => 'nullable|string|max:1000',
        ], [
            'nombre.required' => 'El nombre de la cancha es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'tipo.required' => 'El tipo de cancha es obligatorio.',
            'tipo.max' => 'El tipo no puede tener más de 100 caracteres.',
            'precio_hora.required' => 'El precio por hora es obligatorio.',
            'precio_hora.numeric' => 'El precio debe ser un número.',
            'precio_hora.min' => 'El precio debe ser mayor o igual a 0.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser: disponible, ocupada o mantenimiento.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ]);

        // Actualizar la cancha con los nuevos datos
        $cancha->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'precio_hora' => $request->precio_hora,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
        ]);

        // Redirigir al listado con mensaje de éxito
        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha "' . $cancha->nombre . '" actualizada exitosamente.');
    }

    /**
     * ELIMINAR - Elimina permanentemente la cancha
     */
    public function eliminar($id)
    {
        $cancha = Cancha::findOrFail($id);
        
        // Guardar el nombre antes de eliminar
        $nombreCancha = $cancha->nombre;
        
        // Eliminar permanentemente
        $cancha->delete();

        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha "' . $nombreCancha . '" eliminada permanentemente.');
    }
}