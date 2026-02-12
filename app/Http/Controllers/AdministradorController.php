<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;  

class AdministradorController extends Controller
{
    // Muestra el listado de administradores
    public function listado()
    {
        $administradores = Administrador::all();
        return view('admins.listado', compact('administradores'));
    }

    // Muestra el formulario de registro
    public function registro()
    {
        return view('admins.registro');
    }

    // Muestra el formulario de edición
    public function editar($id)
    {
        $administrador = Administrador::findOrFail($id);
        return view('admins.editar', compact('administrador'));
    }

    // Guarda el nuevo administrador (CON IMAGEN)
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:15',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        // Crear administrador
        $administrador = Administrador::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono
        ]);

        // Subir imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "administradores_{$administrador->id}.{$extension}";
            
            // Guardar en storage
            $imagen->storeAs('public/administradores', $nombreImagen);
            
            // Actualizar registro
            $administrador->imagen = $nombreImagen;
            $administrador->save();
        }

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador registrado exitosamente');
    }

    // Actualizar administrador (CON IMAGEN)
    public function actualizar(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email,' . $id,
            'telefono' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        // Actualizar datos
        $administrador->nombre = $request->nombre;
        $administrador->apellido = $request->apellido;
        $administrador->email = $request->email;
        $administrador->telefono = $request->telefono;

        if ($request->filled('password')) {
            $administrador->password = Hash::make($request->password);
        }

        // Actualizar imagen si se subió nueva
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($administrador->imagen) {
                Storage::delete('public/administradores/' . $administrador->imagen);
            }
            
            // Subir nueva imagen
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "administradores_{$administrador->id}.{$extension}";
            $imagen->storeAs('public/administradores', $nombreImagen);
            
            $administrador->imagen = $nombreImagen;
        }

        $administrador->save();

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador actualizado correctamente');
    }

    // Eliminar administrador (CON ELIMINACIÓN DE IMAGEN)
    public function eliminar($id)
    {
        $administrador = Administrador::findOrFail($id);
        
        // Eliminar imagen del storage
        if ($administrador->imagen) {
            Storage::delete('public/administradores/' . $administrador->imagen);
        }
        
        $administrador->delete();

        return redirect()->route('admins.listado')
                        ->with('success', 'Administrador eliminado permanentemente.');
    }
}