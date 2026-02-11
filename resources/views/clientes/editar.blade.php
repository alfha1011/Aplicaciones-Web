@extends('layouts.app')

@section('titulo', 'Editar Clientes')

@section('contenido-principal')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-6">Editar Cliente</h2>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.actualizar', $cliente->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- ID (solo lectura) --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">ID</label>
            <input type="text"
                   value="{{ $cliente->id }}"
                   disabled
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs opacity-70">
        </div>

        {{-- Nombre --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Nombre</label>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre', $cliente->nombre) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        {{-- Apellido --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Apellido</label>
            <input type="text"
                   name="apellido"
                   value="{{ old('apellido', $cliente->apellido) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        {{-- Email --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $cliente->email) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        {{-- Teléfono --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Teléfono</label>
            <input type="text"
                   name="telefono"
                   value="{{ old('telefono', $cliente->telefono) }}"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>
        {{-- Dirección --}}
        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Dirección</label>
            <input type="text"
                   name="direccion"
                   value="{{ old('direccion', $cliente->direccion) }}"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        

        {{-- Botones --}}
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="px-4 py-2 bg-brand text-white rounded-base hover:opacity-90">
                Actualizar
            </button>

            <a href="{{ route('admins.listado') }}"
               class="px-4 py-2 border border-default-medium rounded-base hover:bg-neutral-secondary-medium">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
