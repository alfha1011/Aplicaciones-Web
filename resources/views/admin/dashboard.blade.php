@extends('layouts.app')

@section('contenido-principal')

<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Dashboard Administrativo
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Bienvenido, <span class="font-semibold text-yellow-600">
                            {{ Auth::guard('admin')->user()->nombre }} 
                            {{ Auth::guard('admin')->user()->apellido }}
                        </span>
                        
                        <span class="ml-2 px-3 py-1 text-xs font-semibold rounded-full
                            @if(Auth::guard('admin')->user()->esMaster()) 
                                bg-purple-100 text-purple-800 border border-purple-300
                            @else 
                                bg-blue-100 text-blue-800 border border-blue-300
                            @endif">
                            {{ Auth::guard('admin')->user()->esMaster() ? '👑 MASTER' : '📋 BASE' }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ Auth::guard('admin')->user()->email }}
                    </p>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold shadow-md">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Administradores</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ \App\Models\Administrador::where('activo', 1)->count() }}
                        </p>
                        @if(Auth::guard('admin')->user()->esMaster())
                            <div class="mt-2 flex gap-2">
                                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded">
                                    {{ \App\Models\Administrador::where('rol', 'master')->count() }} Master
                                </span>
                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                    {{ \App\Models\Administrador::where('rol', 'base')->count() }} Base
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

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

        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                Tus Permisos
            </h2>
            
            @if(Auth::guard('admin')->user()->esMaster())
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-purple-800 font-semibold">
                                Administrador MASTER - Control Total
                            </p>
                            <p class="text-sm text-purple-700 mt-1">
                                Tienes acceso completo al sistema. Puedes crear, editar y <strong>eliminar</strong> administradores, clientes y canchas.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800 font-semibold">
                                Administrador BASE - Permisos Limitados
                            </p>
                            <p class="text-sm text-blue-700 mt-1">
                                Puedes crear y editar registros, pero <strong>no puedes eliminar</strong> administradores, clientes ni canchas. Solo el administrador MASTER tiene esos permisos.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

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