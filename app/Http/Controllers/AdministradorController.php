<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    // Muestra el listado de administradores
    public function listado()
    {
        $administradores = Administrador::all();
        return view('admins.listado', compact('administradores'));
    }

    public function editar($id)
    {
        $administrador = Administrador::findOrFail($id);
        return view('admins.editar', compact('administrador'));
    }
    // Muestra el formulario de registro
    public function registro()
    {
        return view('admins.registro');
    }

    public function actualizar(Request $request, $id)
{
    $administrador = Administrador::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'email' => 'required|email|unique:administradores,email,' . $id,
        'telefono' => 'nullable|string|max:15',
        'password' => 'nullable|string|min:6'
    ]);

    $administrador->nombre = $request->nombre;
    $administrador->apellido = $request->apellido;
    $administrador->email = $request->email;
    $administrador->telefono = $request->telefono;

    if ($request->filled('password')) {
        $administrador->password = Hash::make($request->password);
    }

    $administrador->save();

    return redirect()->route('admins.listado')
        ->with('success', 'Administrador actualizado correctamente');
}

    // Guarda el nuevo administrador
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:15'
        ]);

        Administrador::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono
        ]);

        return redirect()->route('admins.listado')
            ->with('success', 'Administrador registrado exitosamente');
    }
     public function eliminar($id)
    {
        $administrador = Administrador::findOrFail($id);
        $administrador->delete();

        return redirect()->route('admins.listado')
                        ->with('success', 'Administrador eliminado permanentemente.');
    }
}