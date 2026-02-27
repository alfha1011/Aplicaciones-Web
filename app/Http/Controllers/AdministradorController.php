<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdministradorController extends Controller
{
    public function listado()
    {
        $administradores = Administrador::all();
        return view('admins.listado', compact('administradores'));
    }

    public function registro()
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden crear nuevos administradores.']);
        }

        return view('admins.registro');
    }

    public function editar($id)
    {
        $administrador = Administrador::findOrFail($id);
        return view('admins.editar', compact('administrador'));
    }

    public function guardar(Request $request)
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden crear nuevos administradores.']);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|min:6',
            'telefono' => 'required|string|max:15',
            'rol' => 'required|in:master,base',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'rol.required' => 'Debes seleccionar un rol',
            'telefono.required' => 'El teléfono es obligatorio',
        ]);

        $administrador = Administrador::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'activo' => 1,
            'rol' => $request->rol
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "administradores_{$administrador->id}.{$extension}";
            
            $imagen->storeAs('public/administradores', $nombreImagen);
            
            $administrador->imagen = $nombreImagen;
            $administrador->save();
        }

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador creado correctamente.');
    }

    public function actualizar(Request $request, $id)
    {
        $administrador = Administrador::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email,' . $id,
            'telefono' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'rol' => 'required|in:master,base',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'rol.required' => 'Debes seleccionar un rol',
        ]);

        if ($administrador->rol !== $request->rol && !Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden cambiar roles.']);
        }

        $administrador->nombre = $request->nombre;
        $administrador->apellido = $request->apellido;
        $administrador->email = $request->email;
        $administrador->telefono = $request->telefono;
        $administrador->rol = $request->rol;

        if ($request->filled('password')) {
            $administrador->password = Hash::make($request->password);
        }

        if ($request->hasFile('imagen')) {
          
            if ($administrador->imagen) {
                Storage::delete('public/administradores/' . $administrador->imagen);
            }
            
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "administradores_{$administrador->id}.{$extension}";
            $imagen->storeAs('public/administradores', $nombreImagen);
            
            $administrador->imagen = $nombreImagen;
        }

        $administrador->save();

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador actualizado correctamente.');
    }

    public function eliminar($id)
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden eliminar registros.']);
        }

        $administrador = Administrador::findOrFail($id);

        if ($administrador->id === Auth::guard('admin')->user()->id) {
            return redirect()->back()->withErrors(['error' => 'No puedes eliminarte a ti mismo.']);
        }

        if ($administrador->imagen) {
            Storage::delete('public/administradores/' . $administrador->imagen);
        }

        $administrador->delete();

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador eliminado correctamente.');
    }
}