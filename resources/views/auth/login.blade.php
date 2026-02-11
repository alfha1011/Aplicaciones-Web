    @extends('layouts.app')

    @section('contenido-principal')
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
                <div>
                    <div class="flex justify-center">

                    </div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Canchas de Futbol
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Panel Administrativo
                    </p>
                </div>

                <form class="mt-8 space-y-6" action="{{ route('inicio') }}" method="POST">
                    @csrf
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div class="mb-4">
                            <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">Correo
                                Electrónico</label>
                            <input id="email-address" name="email" type="email" autocomplete="email" required
                                class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm transition-all duration-200"
                                placeholder="ejemplo@correo.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm transition-all duration-200"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                Recordarme
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-yellow-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-lg hover:shadow-red-500/30">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">

                            </span>
                            Iniciar Sesión
                        </button>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('google.login') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg"
                                alt="Google logo">
                            Entrar con Google
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <style>
            #sidebar,
            #navbar {
                display: none !important;
            }
        </style>
    @endsection
