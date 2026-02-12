@extends('layouts.app')

@section('titulo', 'Canchas Deshabilitadas')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Canchas Deshabilitadas</h1>
            <p class="text-gray-600 mt-2">Canchas que fueron deshabilitadas temporalmente</p>
        </div>
        <a href="{{ route('canchas.listado') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Canchas Activas
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-orange-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Imágenes</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Tipo</th>
                    <th scope="col" class="px-6 py-3">Precio/Hora</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Descripción</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($canchas as $cancha)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $cancha->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-1">
                            @if($cancha->imagen1)
                                <img src="{{ asset('storage/canchas/' . $cancha->imagen1) }}" 
                                     alt="Img1" class="h-10 w-10 object-cover rounded">
                            @endif
                            @if($cancha->imagen2)
                                <img src="{{ asset('storage/canchas/' . $cancha->imagen2) }}" 
                                     alt="Img2" class="h-10 w-10 object-cover rounded">
                            @endif
                            @if($cancha->imagen3)
                                <img src="{{ asset('storage/canchas/' . $cancha->imagen3) }}" 
                                     alt="Img3" class="h-10 w-10 object-cover rounded">
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $cancha->nombre }}</td>
                    <td class="px-6 py-4">{{ $cancha->tipo }}</td>
                    <td class="px-6 py-4">${{ number_format($cancha->precio_hora, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">
                            Deshabilitada
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $cancha->descripcion ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('canchas.habilitar', $cancha->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-3 py-2 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700"
                                    onclick="return confirm('¿Desea habilitar esta cancha nuevamente?')">
                                Habilitar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center">
                        <div class="text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">¡Genial! No hay canchas deshabilitadas</p>
                            <p class="text-sm">Todas las canchas están activas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection