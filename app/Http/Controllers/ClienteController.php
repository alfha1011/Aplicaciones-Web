<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClienteController extends Controller
{
    // Muestra el listado de clientes
    public function listado()
    {
        $clientes = Cliente::all();
        return view('clientes.listado', compact('clientes'));
    }

    // Muestra el formulario de registro
    public function registro()
    {
        return view('clientes.registro');
    }

    // Guarda el nuevo cliente
   public function guardar(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'email' => 'required|email|unique:clientes,email',
        'telefono' => 'required|string|max:15',
        'direccion' => 'nullable|string'
    ]);

    $lat = null;
    $lng = null;

    // 🗺️ GEOCODIFICACIÓN
    if ($request->filled('direccion')) {
        $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
            'q'   => $request->direccion,
            'key' => env('GEOCODING_API_KEY'),
            'limit' => 1
        ]);

        if ($response->successful() && count($response['results']) > 0) {
            $lat = $response['results'][0]['geometry']['lat'];
            $lng = $response['results'][0]['geometry']['lng'];
        }
    }

    Cliente::create([
        'nombre'    => $request->nombre,
        'apellido'  => $request->apellido,
        'email'     => $request->email,
        'telefono'  => $request->telefono,
        'direccion' => $request->direccion,
        'latitud'   => $lat,
        'longitud'  => $lng,
    ]);

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
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'email' => 'required|email|unique:clientes,email,' . $id,
        'telefono' => 'nullable|string|max:15',
        'password' => 'nullable|string|min:6'
    ]);

    $cliente->nombre = $request->nombre;
    $cliente->apellido = $request->apellido;
    $cliente->email = $request->email;
    $cliente->telefono = $request->telefono;
    $cliente->direccion = $request->direccion;

    if ($request->filled('password')) {
        $cliente->password = Hash::make($request->password);
    }
    $lat = $cliente->latitud;
$lng = $cliente->longitud;

if ($request->filled('direccion') && $request->direccion !== $cliente->direccion) {
    $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
        'q' => $request->direccion,
        'key' => env('GEOCODING_API_KEY'),
        'limit' => 1
    ]);

    if ($response->successful() && count($response['results']) > 0) {
        $lat = $response['results'][0]['geometry']['lat'];
        $lng = $response['results'][0]['geometry']['lng'];
    }
}

$cliente->latitud = $lat;
$cliente->longitud = $lng;
    $cliente->save();

    return redirect()->route('clientes.listado')
        ->with('success', 'Cliente actualizado correctamente');
}
     public function eliminar($id)
    {
        $cliente = Cliente::findOrFail($id);
        
        // Guardar el nombre antes de eliminar
        $nombreCliente = $cliente->nombre;
        
        // Eliminar permanentemente
        $cliente->delete();

        return redirect()->route('clientes.listado')
                        ->with('success', 'Cliente "' . $nombreCliente . '" eliminado permanentemente.');
    }
}