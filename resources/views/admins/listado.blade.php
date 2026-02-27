@extends('layouts.app')

@section('contenido-principal')

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Listado de Administradores</h1>
        
        @if(Auth::guard('admin')->user()->esMaster())
            <a href="{{ route('admins.registro') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Nuevo Administrador
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($administradores as $admin)
                <tr>
                    <td class="px-6 py-4">
                        @if($admin->imagen)
                            <img src="{{ asset('storage/administradores/' . $admin->imagen) }}"
                                 alt="Foto" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <span class="text-gray-400 text-xs">Sin foto</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $admin->nombre }} {{ $admin->apellido }}</td>
                    <td class="px-6 py-4">{{ $admin->email }}</td>
                    <td class="px-6 py-4">{{ $admin->telefono }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            @if($admin->rol === 'master') bg-purple-100 text-purple-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($admin->rol) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            @if($admin->activo) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $admin->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">

                            <a href="{{ route('admins.editar', $admin->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Editar
                            </a>

                            @if(Auth::guard('admin')->user()->esMaster() && $admin->id !== Auth::guard('admin')->user()->id)
                                <form action="{{ route('admins.eliminar', $admin->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este administrador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection