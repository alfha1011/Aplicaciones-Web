@extends('layouts.app')

@section('titulo', 'Listado de Canchas')

@section('contenido-principal')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Lista de Canchas</h1>
        <div class="flex gap-3">
            <!--ver deshabilitadas -->
            <a href="{{ route('canchas.deshabilitadas') }}"
                class="text-red-600 hover:text-red-800 font-medium flex items-center gap-2 border border-red-600 px-4 py-2 rounded-lg">
                Ver Deshabilitadas
            </a>

            <a href="{{ route('canchas.registro') }}"
                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Nueva Cancha
            </a>
        </div>
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
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Tipo</th>
                    <th scope="col" class="px-6 py-3">Precio/Hora</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Descripción</th>
                    <th scope="col" class="px-6 py-3">H/D</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>

                </tr>
            </thead>
            <tbody>
                @forelse($canchas as $cancha)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $cancha->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $cancha->nombre }}</td>
                        <td class="px-6 py-4">{{ $cancha->tipo }}</td>
                        <td class="px-6 py-4">${{ number_format($cancha->precio_hora, 2) }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded 
                            @if ($cancha->estado == 'disponible') bg-green-100 text-green-800
                            @elseif($cancha->estado == 'mantenimiento') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($cancha->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $cancha->descripcion ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('canchas.deshabilitar', $cancha->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 text-xs font-medium rounded-lg bg-orange-100 text-orange-800 hover:bg-orange-200"
                                    onclick="return confirm('¿Desea deshabilitar esta cancha? Podrá habilitarla nuevamente desde la sección de Deshabilitadas.')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>

                        <td class="px-6 py-4">
                            <a href="{{ route('canchas.editar', $cancha->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                            <form action="{{ route('canchas.eliminar', $cancha->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('¿Estás seguro de eliminar esta cancha?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No hay canchas registradas
                        </td>
                    </tr>
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Nota:</strong> Las canchas deshabilitadas no aparecen en esta lista.
                            <a href="{{ route('canchas.deshabilitadas') }}" class="underline font-medium">Ver canchas
                                deshabilitadas</a>
                        </p>
                    </div>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
@endsection
