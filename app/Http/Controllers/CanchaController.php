<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // 👈 IMPORTANTE

class CanchaController extends Controller
{
    /**
     * LISTADO - Mostrar solo canchas HABILITADAS (activo = 1)
     */
    public function listado()
    {
        $canchas = Cancha::where('activo', 1)->get();
        return view('canchas.listado', compact('canchas'));
    }

    /**
     * LISTADO DE DESHABILITADAS - Mostrar solo canchas DESHABILITADAS (activo = 0)
     */
    public function deshabilitadas()
    {
        $canchas = Cancha::where('activo', 0)->get();
        return view('canchas.deshabilitadas', compact('canchas'));
    }

    /**
     * DESHABILITAR - Cambia el estado de activo a inactivo (1 → 0)
     */
    public function deshabilitar($id)
    {
        $cancha = Cancha::findOrFail($id);
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
     * GUARDAR - Guardar nueva cancha (CON 3 IMÁGENES)
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
            'imagen1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'imagen2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen3' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
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
            'imagen1.required' => 'La imagen 1 es obligatoria.',
            'imagen1.image' => 'El archivo debe ser una imagen.',
            'imagen2.required' => 'La imagen 2 es obligatoria.',
            'imagen2.image' => 'El archivo debe ser una imagen.',
            'imagen3.required' => 'La imagen 3 es obligatoria.',
            'imagen3.image' => 'El archivo debe ser una imagen.',
        ]);

        // Crear la cancha (por defecto activo = 1)
        $cancha = Cancha::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'precio_hora' => $request->precio_hora,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'activo' => 1,
        ]);

        // Subir 3 imágenes
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("imagen$i")) {
                $imagen = $request->file("imagen$i");
                $extension = $imagen->getClientOriginalExtension();
                $nombreImagen = "canchas_{$cancha->id}_{$i}.{$extension}";
                
                $imagen->storeAs('public/canchas', $nombreImagen);
                
                $campoImagen = "imagen$i";
                $cancha->$campoImagen = $nombreImagen;
            }
        }
        
        $cancha->save();

        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha creada exitosamente.');
    }

  
    public function editar($id)
    {
        $cancha = Cancha::findOrFail($id);
        return view('canchas.editar', compact('cancha'));
    }

   
    public function actualizar(Request $request, $id)
    {
        $cancha = Cancha::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'precio_hora' => 'required|numeric|min:0',
            'estado' => 'required|in:disponible,ocupada,mantenimiento',
            'descripcion' => 'nullable|string|max:1000',
            'imagen1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'imagen2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'imagen3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'imagen1.image' => 'El archivo debe ser una imagen.',
            'imagen2.image' => 'El archivo debe ser una imagen.',
            'imagen3.image' => 'El archivo debe ser una imagen.',
        ]);

        // Actualizar datos
        $cancha->nombre = $request->nombre;
        $cancha->tipo = $request->tipo;
        $cancha->precio_hora = $request->precio_hora;
        $cancha->estado = $request->estado;
        $cancha->descripcion = $request->descripcion;

        // Actualizar imágenes si se subieron nuevas
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("imagen$i")) {
                // Eliminar imagen anterior
                $campoImagen = "imagen$i";
                if ($cancha->$campoImagen) {
                    Storage::delete('public/canchas/' . $cancha->$campoImagen);
                }
                
                $imagen = $request->file("imagen$i");
                $extension = $imagen->getClientOriginalExtension();
                $nombreImagen = "canchas_{$cancha->id}_{$i}.{$extension}";
                $imagen->storeAs('public/canchas', $nombreImagen);
                
                $cancha->$campoImagen = $nombreImagen;
            }
        }

        $cancha->save();

        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha "' . $cancha->nombre . '" actualizada exitosamente.');
    }

   
    public function eliminar($id)
    {
        $cancha = Cancha::findOrFail($id);
        
        // Eliminar las 3 imágenes del storage
        for ($i = 1; $i <= 3; $i++) {
            $campoImagen = "imagen$i";
            if ($cancha->$campoImagen) {
                Storage::delete('public/canchas/' . $cancha->$campoImagen);
            }
        }
        
        $nombreCancha = $cancha->nombre;
        $cancha->delete();

        return redirect()->route('canchas.listado')
                        ->with('success', 'Cancha "' . $nombreCancha . '" eliminada permanentemente.');
    }
}