<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CanchaController extends Controller
{
    public function listado()
    {
        $canchas = Cancha::where('activo', 1)->get();
        return view('canchas.listado', compact('canchas'));
    }

    public function deshabilitadas()
    {
        $canchas = Cancha::where('activo', 0)->get();
        return view('canchas.deshabilitadas', compact('canchas'));
    }

    public function deshabilitar($id)
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden deshabilitar canchas.']);
        }

        $cancha = Cancha::findOrFail($id);
        $cancha->activo = 0;
        $cancha->save();
        
        return redirect()->route('canchas.listado')
            ->with('success', 'Cancha "' . $cancha->nombre . '" deshabilitada correctamente.');
    }

    public function habilitar($id)
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden habilitar canchas.']);
        }

        $cancha = Cancha::findOrFail($id);
        $cancha->activo = 1;
        $cancha->save();
        
        return redirect()->route('canchas.deshabilitadas')
            ->with('success', 'Cancha "' . $cancha->nombre . '" habilitada correctamente.');
    }

    public function registro()
    {
        return view('canchas.registro');
    }

    public function guardar(Request $request)
    {
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
            'tipo.required' => 'El tipo de cancha es obligatorio.',
            'precio_hora.required' => 'El precio por hora es obligatorio.',
            'precio_hora.numeric' => 'El precio debe ser un número.',
            'precio_hora.min' => 'El precio debe ser mayor o igual a 0.',
            'estado.required' => 'El estado es obligatorio.',
            'imagen1.required' => 'La imagen 1 es obligatoria.',
            'imagen2.required' => 'La imagen 2 es obligatoria.',
            'imagen3.required' => 'La imagen 3 es obligatoria.',
        ]);

        $cancha = Cancha::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'precio_hora' => $request->precio_hora,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'activo' => 1,
        ]);

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
            'tipo.required' => 'El tipo de cancha es obligatorio.',
            'precio_hora.required' => 'El precio por hora es obligatorio.',
        ]);

        $cancha->nombre = $request->nombre;
        $cancha->tipo = $request->tipo;
        $cancha->precio_hora = $request->precio_hora;
        $cancha->estado = $request->estado;
        $cancha->descripcion = $request->descripcion;

        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("imagen$i")) {

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
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden eliminar canchas.']);
        }

        $cancha = Cancha::findOrFail($id);
        
        for ($i = 1; $i <= 3; $i++) {
            $campoImagen = "imagen$i";
            if ($cancha->$campoImagen) {
                Storage::delete('public/canchas/' . $cancha->$campoImagen);
            }
        }
        
        $nombreCancha = $cancha->nombre;
        $cancha->delete();

        return redirect()->route('canchas.listado')
            ->with('success', 'Cancha "' . $nombreCancha . '" eliminada correctamente.');
    }
}