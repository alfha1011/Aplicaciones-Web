@extends('layouts.app')

@section('titulo', 'Editar Cancha')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Cancha</h1>
        <p class="text-gray-600 mt-2">Modifica los datos de la cancha</p>
    </div>

    <!-- Botón Volver -->
    <div class="mb-4">
        <a href="{{ route('canchas.listado') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al listado
        </a>
    </div>

    <!-- Mensajes de error -->
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

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <form action="{{ route('canchas.actualizar', $cancha->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Campo: Nombre -->
            <div>
                <label for="nombre" class="block mb-2.5 text-sm font-medium text-heading">
                    Nombre de la Cancha <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre"
                    value="{{ old('nombre', $cancha->nombre) }}"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs placeholder:text-body @error('nombre') border-red-500 @enderror" 
                    placeholder="Ej: Cancha de Fútbol 1" 
                    required 
                />
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Tipo -->
            <div>
                <label for="tipo" class="block mb-2.5 text-sm font-medium text-heading">
                    Tipo de Cancha <span class="text-red-600">*</span>
                </label>
                <select 
                    id="tipo" 
                    name="tipo"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs @error('tipo') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar tipo...</option>
                    <option value="Fútbol" {{ old('tipo', $cancha->tipo) == 'Fútbol' ? 'selected' : '' }}>Fútbol</option>
                    <option value="Fútbol 7" {{ old('tipo', $cancha->tipo) == 'Fútbol 7' ? 'selected' : '' }}>Fútbol 7</option>
                    <option value="Fútbol 5" {{ old('tipo', $cancha->tipo) == 'Fútbol 5' ? 'selected' : '' }}>Fútbol 5</option>
                    <option value="Básquetbol" {{ old('tipo', $cancha->tipo) == 'Básquetbol' ? 'selected' : '' }}>Básquetbol</option>
                    <option value="Tenis" {{ old('tipo', $cancha->tipo) == 'Tenis' ? 'selected' : '' }}>Tenis</option>
                    <option value="Vóleybol" {{ old('tipo', $cancha->tipo) == 'Vóleybol' ? 'selected' : '' }}>Vóleybol</option>
                    <option value="Pádel" {{ old('tipo', $cancha->tipo) == 'Pádel' ? 'selected' : '' }}>Pádel</option>
                    <option value="Otro" {{ old('tipo', $cancha->tipo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('tipo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Precio por Hora -->
            <div>
                <label for="precio_hora" class="block mb-2.5 text-sm font-medium text-heading">
                    Precio por Hora <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                    <input 
                        type="number" 
                        id="precio_hora" 
                        name="precio_hora"
                        value="{{ old('precio_hora', $cancha->precio_hora) }}"
                        step="0.01"
                        min="0"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full pl-8 pr-3.5 py-3 shadow-xs placeholder:text-body @error('precio_hora') border-red-500 @enderror" 
                        placeholder="0.00" 
                        required 
                    />
                </div>
                @error('precio_hora')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Estado -->
            <div>
                <label for="estado" class="block mb-2.5 text-sm font-medium text-heading">
                    Estado <span class="text-red-600">*</span>
                </label>
                <select 
                    id="estado" 
                    name="estado"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-base rounded-base focus:ring-brand focus:border-brand block w-full px-3.5 py-3 shadow-xs @error('estado') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccionar estado...</option>
                    <option value="disponible" {{ old('estado', $cancha->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupada" {{ old('estado', $cancha->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="mantenimiento" {{ old('estado', $cancha->estado) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                </select>
                @error('estado')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Descripción -->
            <div>
                <label for="descripcion" class="block mb-2.5 text-sm font-medium text-heading">
                    Descripción (Opcional)
                </label>
                <textarea 
                    id="descripcion" 
                    name="descripcion"
                    rows="4"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body @error('descripcion') border-red-500 @enderror"
                    placeholder="Descripción adicional de la cancha, características especiales, etc."
                >{{ old('descripcion', $cancha->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-4">
                <button 
                    type="submit" 
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Actualizar Cancha
                </button>
                <a 
                    href="{{ route('canchas.listado') }}" 
                    class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</div>
@endsection