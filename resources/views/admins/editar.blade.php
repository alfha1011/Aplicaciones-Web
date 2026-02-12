@extends('layouts.app')

@section('titulo', 'Editar Administrador')

@section('contenido-principal')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-6">Editar Administrador</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admins.actualizar', $administrador->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">ID</label>
            <input type="text"
                   value="{{ $administrador->id }}"
                   disabled
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs opacity-70">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Nombre</label>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre', $administrador->nombre) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Apellido</label>
            <input type="text"
                   name="apellido"
                   value="{{ old('apellido', $administrador->apellido) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $administrador->email) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Teléfono</label>
            <input type="text"
                   name="telefono"
                   value="{{ old('telefono', $administrador->telefono) }}"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div class="mb-4">
            <label class="block mb-2.5 text-sm font-medium text-heading">Foto Actual</label>
            @if($administrador->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/administradores/' . $administrador->imagen) }}" 
                         alt="Foto" class="h-32 w-32 object-cover rounded-lg border">
                </div>
            @else
                <p class="text-gray-500 text-sm mb-2">No hay foto</p>
            @endif
        </div>

        <div class="mb-4">
            <label class="block mb-2.5 text-sm font-medium text-heading" for="imagen">
                Nueva Foto (opcional)
            </label>
            <input type="file" 
                   name="imagen" 
                   id="imagen" 
                   accept="image/*"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs">
            <p class="text-xs text-gray-500 mt-1">Dejar vacío para mantener la foto actual.</p>
        </div>

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