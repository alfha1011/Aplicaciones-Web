@extends('layouts.app')

@section('contenido-principal')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Canchas de Futbol
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Panel Administrativo
            </p>
        </div>

        {{-- ERRORES GENERALES --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- MENSAJE DE ÉXITO (si existe) --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login.procesar') }}" method="POST">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo Electrónico
                    </label>
                    <input 
                        id="email"
                        name="email" 
                        type="email" 
                        required
                        value="{{ old('email') }}"
                        class="appearance-none rounded-lg relative block w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm transition-all duration-200"
                        placeholder="ejemplo@correo.com"
                        autocomplete="email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input 
                        id="password"
                        name="password" 
                        type="password" 
                        required
                        class="appearance-none rounded-lg relative block w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm transition-all duration-200"
                        placeholder="••••••••"
                        autocomplete="current-password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- REMEMBER --}}
            <div class="flex items-center">
                <input 
                    id="remember"
                    name="remember" 
                    type="checkbox"
                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded cursor-pointer">
                <label for="remember" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                    Recordarme
                </label>
            </div>

            {{-- BOTÓN LOGIN --}}
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 text-sm font-bold rounded-xl text-white bg-yellow-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-yellow-300 group-hover:text-red-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Iniciar Sesión
                </button>
            </div>

            {{-- DIVIDER --}}
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">O continúa con</span>
                </div>
            </div>

            {{-- GOOGLE LOGIN --}}
            <div>
                <a href="{{ route('google.login') }}"
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                    <img class="h-5 w-5 mr-2"
                        src="https://www.svgrepo.com/show/475656/google-color.svg"
                        alt="Google logo">
                    Entrar con Google
                </a>
            </div>

        </form>

        {{-- Footer --}}
        <div class="text-center">
            <p class="text-xs text-gray-500">
                Sistema de Reservas de Canchas © 2026
            </p>
        </div>
    </div>
</div>

<style>
    #sidebar,
    #navbar {
        display: none !important;
    }
</style>
@endsection