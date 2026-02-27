<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function listado()
    {
        $clientes = Cliente::all();
        return view('clientes.listado', compact('clientes'));
    }

    public function registro()
    {
        return view('clientes.registro');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email',
            'telefono' => 'required|string|max:15',
            'direccion'=> 'nullable|string',
            'imagen'   => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nombre.required'   => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required'    => 'El email es obligatorio',
            'email.unique'      => 'Este email ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'imagen.required'   => 'La imagen es obligatoria',
        ]);

        $lat = $request->filled('latitud') ? $request->latitud : null;
        $lng = $request->filled('longitud') ? $request->longitud : null;

        if (!$lat && $request->filled('direccion')) {
            $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
                'q'     => $request->direccion,
                'key'   => env('GEOCODING_API_KEY'),
                'limit' => 1
            ]);

            if ($response->successful() && count($response['results']) > 0) {
                $lat = $response['results'][0]['geometry']['lat'];
                $lng = $response['results'][0]['geometry']['lng'];
            }
        }

        $cliente = Cliente::create([
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'email'     => $request->email,
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
            'latitud'   => $lat,
            'longitud'  => $lng,
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "clientes_{$cliente->id}.{$extension}";
            
            $imagen->storeAs('public/clientes', $nombreImagen);
            
            $cliente->imagen = $nombreImagen;
            $cliente->save();
        }

        return redirect()->route('clientes.listado')
            ->with('success', 'Cliente registrado exitosamente');
    }

    public function editar($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.editar', compact('cliente'));
    }

    public function actualizar(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email,' . $id,
            'telefono' => 'nullable|string|max:15',
            'direccion'=> 'nullable|string',
            'password' => 'nullable|string|min:6',
            'imagen'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nombre.required'   => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required'    => 'El email es obligatorio',
            'email.unique'      => 'Este email ya está registrado',
        ]);

        $cliente->nombre    = $request->nombre;
        $cliente->apellido  = $request->apellido;
        $cliente->email     = $request->email;
        $cliente->telefono  = $request->telefono;
        $cliente->direccion = $request->direccion;

        if ($request->filled('password')) {
            $cliente->password = Hash::make($request->password);
        }

        if ($request->filled('latitud') && $request->filled('longitud')) {
            $cliente->latitud  = $request->latitud;
            $cliente->longitud = $request->longitud;
        } elseif ($request->filled('direccion') && $request->direccion !== $cliente->direccion) {

            $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
                'q'     => $request->direccion,
                'key'   => env('GEOCODING_API_KEY'),
                'limit' => 1
            ]);

            if ($response->successful() && count($response['results']) > 0) {
                $cliente->latitud  = $response['results'][0]['geometry']['lat'];
                $cliente->longitud = $response['results'][0]['geometry']['lng'];
            }
        }

        if ($request->hasFile('imagen')) {
            if ($cliente->imagen) {
                Storage::delete('public/clientes/' . $cliente->imagen);
            }
            
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = "clientes_{$cliente->id}.{$extension}";
            $imagen->storeAs('public/clientes', $nombreImagen);
            
            $cliente->imagen = $nombreImagen;
        }

        $cliente->save();

        return redirect()->route('clientes.listado')
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function eliminar($id)
    {
        if (!Auth::guard('admin')->user()->esMaster()) {
            return redirect()->back()->withErrors(['error' => 'Solo los administradores MASTER pueden eliminar clientes.']);
        }

        $cliente = Cliente::findOrFail($id);
        
        if ($cliente->imagen) {
            Storage::delete('public/clientes/' . $cliente->imagen);
        }
        
        $nombreCliente = $cliente->nombre;
        $cliente->delete();

        return redirect()->route('clientes.listado')
            ->with('success', 'Cliente "' . $nombreCliente . '" eliminado correctamente.');
    }
}