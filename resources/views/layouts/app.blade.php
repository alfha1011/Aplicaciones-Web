<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Reservas - @yield('titulo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>
    {{-- header navbar --}}
    <header class="fixed w-full z-20 top-0 start-0">
        <nav class="bg-neutral-primary fixed w-full z-20 top-0 start-0 border-b border-default">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/dashboard" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('img/cross.png') }}" class="h-7" alt="Logo">
                    <span class="self-center text-xl text-heading font-semibold whitespace-nowrap"> Reservas
                        Canchas</span>
                </a>
                
                <div class="flex items-center md:order-2 space-x-3 md:space-x-4">
                    {{-- Nombre del usuario --}}
                    @auth
                    <span class="text-white text-sm hidden md:block">
                        {{ Auth::user()->name }}
                    </span>
                    
                    {{-- Botón de logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition-colors">
                            Salir
                        </button>
                    </form>
                    @endauth
                    
                    <button data-collapse-toggle="navbar-multi-level-dropdown" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary"
                        aria-controls="navbar-multi-level-dropdown" aria-expanded="false">
                        <span class="sr-only">Abrir menú</span>
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M5 7h14M5 12h14M5 17h14" />
                        </svg>
                    </button>
                </div>
                
                <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level-dropdown">
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-default rounded-base bg-neutral-secondary-soft md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-neutral-primary">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                                <span aria-current="page">Inicio</span>
                            </a>
                        </li>
                        <li>
                            <button id="multiLevelDropdownButton" data-dropdown-toggle="multi-dropdown"
                                class="flex items-center justify-between w-full py-2 px-3 rounded font-medium text-heading md:w-auto hover:bg-neutral-tertiary md:hover:bg-transparent md:border-0 md:hover:text-fg-brand md:p-0">
                                Catálogo
                                <svg class="w-4 h-4 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 9-7 7-7-7" />
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="multi-dropdown"
                                class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
                                <ul class="p-2 text-sm text-body font-medium"
                                    aria-labelledby="multiLevelDropdownButton">

                                    {{-- ADMINISTRADORES --}}
                                    <li>
                                        <button id="dropdownAdmins" data-dropdown-toggle="doubleDropdownAdmins"
                                            data-dropdown-placement="right-start" type="button"
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                            Administradores
                                            <svg class="h-4 w-4 ms-auto rtl:rotate-180" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div id="doubleDropdownAdmins"
                                            class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
                                            <ul class="p-2 text-sm text-body font-medium">
                                                <li>
                                                    <a href="{{ route('admins.registro') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Formulario</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admins.listado') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Listado</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    {{-- CLIENTES --}}
                                    <li>
                                        <button id="dropdownClientes" data-dropdown-toggle="doubleDropdownClientes"
                                            data-dropdown-placement="right-start" type="button"
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                            Clientes
                                            <svg class="h-4 w-4 ms-auto rtl:rotate-180" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div id="doubleDropdownClientes"
                                            class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
                                            <ul class="p-2 text-sm text-body font-medium">
                                                <li>
                                                    <a href="{{ route('clientes.registro') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Formulario</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('clientes.listado') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Listado</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    {{-- CANCHAS --}}
                                    <li>
                                        <button id="dropdownCanchas" data-dropdown-toggle="doubleDropdownCanchas"
                                            data-dropdown-placement="right-start" type="button"
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                            Canchas
                                            <svg class="h-4 w-4 ms-auto rtl:rotate-180" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div id="doubleDropdownCanchas"
                                            class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
                                            <ul class="p-2 text-sm text-body font-medium">
                                                <li>
                                                    <a href="{{ route('canchas.registro') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Formulario</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('canchas.listado') }}"
                                                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                                        Listado</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- contenido principal --}}
    <main class="relative pt-20 overflow-hidden">
        <div class="relative z-10">
            @yield('contenido-principal')
        </div>
    </main>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>

</html>