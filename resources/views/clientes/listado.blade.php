@extends('layouts.app')

@section('titulo', 'Listado de Clientes')

@section('contenido-principal')
    <div class="container mx-auto px-4 py-8">

        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Lista de Clientes</h1>
            <a href="{{ route('clientes.registro') }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Nuevo Cliente
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Apellido</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Teléfono</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Ubicación</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $cliente->id }}</td>
                            <td class="px-6 py-4">
                                @if($cliente->imagen)
                                    <img src="{{ asset('storage/clientes/' . $cliente->imagen) }}"
                                         alt="Foto" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <span class="text-gray-400 text-xs">Sin foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $cliente->nombre }}</td>
                            <td class="px-6 py-4">{{ $cliente->apellido }}</td>
                            <td class="px-6 py-4">{{ $cliente->email }}</td>
                            <td class="px-6 py-4">{{ $cliente->telefono }}</td>
                            <td class="px-6 py-4">{{ $cliente->direccion ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($cliente->latitud && $cliente->longitud)
                                    <div id="map-{{ $cliente->id }}" class="w-48 h-32 rounded"></div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const map = L.map('map-{{ $cliente->id }}')
                                                .setView([{{ $cliente->latitud }}, {{ $cliente->longitud }}], 30);

                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                attribution: '&copy; OpenStreetMap'
                                            }).addTo(map);

                                            L.marker([{{ $cliente->latitud }}, {{ $cliente->longitud }}])
                                                .addTo(map)
                                                .bindPopup("{{ $cliente->nombre }} {{ $cliente->apellido }}");
                                        });
                                    </script>
                                @else
                                    <span class="text-gray-400">Sin ubicación</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('clientes.editar', $cliente->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>

                                {{-- Eliminar: SOLO MASTER --}}
                                @if(Auth::guard('admin')->user()->esMaster())
                                    <form action="{{ route('clientes.eliminar', $cliente->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                No hay clientes registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection