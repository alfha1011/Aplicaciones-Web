@extends('layouts.app')

@section('contenido-principal')

<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header con nombre del admin --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Dashboard Administrativo
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Bienvenido, <span class="font-semibold text-yellow-600">{{ Auth::guard('admin')->user()->nombre }} {{ Auth::guard('admin')->user()->apellido }}</span>
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ Auth::guard('admin')->user()->email }}
                    </p>
                </div>
                
                {{-- Botón de logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold shadow-md">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Administradores --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Administradores</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ \App\Models\Administrador::where('activo', 1)->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Clientes --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Clientes</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ \App\Models\Cliente::count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Canchas --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Canchas Disponibles</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ \App\Models\Cancha::where('activo', 1)->count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- Accesos rápidos --}}
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Accesos Rápidos</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admins.listado') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                    <p class="font-semibold text-gray-800">Administradores</p>
                    <p class="text-sm text-gray-500">Gestionar administradores</p>
                </a>
                <a href="{{ route('clientes.listado') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                    <p class="font-semibold text-gray-800">Clientes</p>
                    <p class="text-sm text-gray-500">Gestionar clientes</p>
                </a>
                <a href="{{ route('canchas.listado') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                    <p class="font-semibold text-gray-800">Canchas</p>
                    <p class="text-sm text-gray-500">Gestionar canchas</p>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection