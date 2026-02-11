@extends('layouts.app')

@section('titulo', 'Listado de Administradores')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Lista de Administradores</h1>
        <a href="{{ route('admins.registro') }}" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5">
            Nuevo Administrador
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Apellido</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @forelse($administradores as $admin)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $admin->id }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $admin->nombre }}</td>
                    <td class="px-6 py-4">{{ $admin->apellido }}</td>
                    <td class="px-6 py-4">{{ $admin->email }}</td>
                    <td class="px-6 py-4">{{ $admin->telefono ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                            <a href="{{ route('admins.editar', $admin->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                            <form action="{{ route('admins.eliminar', $admin->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('¿Estás seguro de eliminar esta cancha?')">Eliminar</button>
                            </form>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No hay administradores registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection