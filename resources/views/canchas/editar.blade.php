@extends('layouts.app')

@section('titulo', 'Editar Cancha')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Cancha</h1>
        <p class="text-gray-600 mt-2">Modifica los datos de la cancha</p>
    </div>

    <div class="mb-4">
        <a href="{{ route('canchas.listado') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al listado
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">¡Error!</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <form action="{{ route('canchas.actualizar', $cancha->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block mb-2.5 text-sm font-medium text-heading">
                    Nombre de la Cancha <span class="text-red-600">*</span>
                </label>
                <input type="text" id="nombre" name="nombre"
                    value="{{ old('nombre', $cancha->nombre) }}"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs @error('nombre') border-red-500 @enderror"
                    placeholder="Ej: Cancha de Fútbol 1" required />
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tipo" class="block mb-2.5 text-sm font-medium text-heading">
                    Tipo de Cancha <span class="text-red-600">*</span>
                </label>
                <select id="tipo" name="tipo"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs @error('tipo') border-red-500 @enderror"
                    required>
                    <option value="">Seleccionar tipo...</option>
                    <option value="Fútbol 5" {{ old('tipo', $cancha->tipo) == 'Fútbol 5' ? 'selected' : '' }}>Fútbol 5</option>
                    <option value="Fútbol 7" {{ old('tipo', $cancha->tipo) == 'Fútbol 7' ? 'selected' : '' }}>Fútbol 7</option>
                    <option value="Fútbol 11" {{ old('tipo', $cancha->tipo) == 'Fútbol 11' ? 'selected' : '' }}>Fútbol 11</option>
                </select>
                @error('tipo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="precio_hora" class="block mb-2.5 text-sm font-medium text-heading">
                    Precio por Hora <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                    <input type="number" id="precio_hora" name="precio_hora"
                        value="{{ old('precio_hora', $cancha->precio_hora) }}"
                        step="0.01" min="0"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full pl-8 pr-3.5 py-3 shadow-xs @error('precio_hora') border-red-500 @enderror"
                        placeholder="0.00" required />
                </div>
                @error('precio_hora')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estado" class="block mb-2.5 text-sm font-medium text-heading">
                    Estado <span class="text-red-600">*</span>
                </label>
                <select id="estado" name="estado"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs @error('estado') border-red-500 @enderror"
                    required>
                    <option value="">Seleccionar estado...</option>
                    <option value="disponible" {{ old('estado', $cancha->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupada" {{ old('estado', $cancha->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="mantenimiento" {{ old('estado', $cancha->estado) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                </select>
                @error('estado')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descripcion" class="block mb-2.5 text-sm font-medium text-heading">
                    Descripción (Opcional)
                </label>
                <textarea id="descripcion" name="descripcion" rows="4"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                    placeholder="Descripción adicional de la cancha...">{{ old('descripcion', $cancha->descripcion) }}</textarea>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold mb-4">Imágenes de la Cancha</h3>
                
                {{-- Imagen 1 --}}
                <div class="mb-4">
                    <label class="block mb-2.5 text-sm font-medium text-heading">Imagen Actual 1</label>
                    @if($cancha->imagen1)
                        <div class="mb-2">
                            <img src="{{ asset('storage/canchas/' . $cancha->imagen1) }}" 
                                 alt="Cancha imagen 1" class="h-32 w-32 object-cover rounded-lg border">
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-2">No hay imagen</p>
                    @endif
                    <label for="imagen1" class="block mb-2 text-sm font-medium text-heading">
                        Nueva Imagen 1 (opcional)
                    </label>
                    <input type="file" id="imagen1" name="imagen1" accept="image/*"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs">
                </div>

                {{-- Imagen 2 --}}
                <div class="mb-4">
                    <label class="block mb-2.5 text-sm font-medium text-heading">Imagen Actual 2</label>
                    @if($cancha->imagen2)
                        <div class="mb-2">
                            <img src="{{ asset('storage/canchas/' . $cancha->imagen2) }}" 
                                 alt="Cancha imagen 2" class="h-32 w-32 object-cover rounded-lg border">
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-2">No hay imagen</p>
                    @endif
                    <label for="imagen2" class="block mb-2 text-sm font-medium text-heading">
                        Nueva Imagen 2 (opcional)
                    </label>
                    <input type="file" id="imagen2" name="imagen2" accept="image/*"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs">
                </div>

                {{-- Imagen 3 --}}
                <div class="mb-4">
                    <label class="block mb-2.5 text-sm font-medium text-heading">Imagen Actual 3</label>
                    @if($cancha->imagen3)
                        <div class="mb-2">
                            <img src="{{ asset('storage/canchas/' . $cancha->imagen3) }}" 
                                 alt="Cancha imagen 3" class="h-32 w-32 object-cover rounded-lg border">
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-2">No hay imagen</p>
                    @endif
                    <label for="imagen3" class="block mb-2 text-sm font-medium text-heading">
                        Nueva Imagen 3 (opcional)
                    </label>
                    <input type="file" id="imagen3" name="imagen3" accept="image/*"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs">
                </div>
                <p class="text-xs text-gray-500">Dejar vacío para mantener la imagen actual.</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Actualizar Cancha
                </button>
                <a href="{{ route('canchas.listado') }}"
                    class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection