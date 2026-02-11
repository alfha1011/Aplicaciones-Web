@extends('layouts.app')

@section('titulo', 'Registro de Cancha')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Registrar Nueva Cancha</h1>

        <form action="{{ route('canchas.guardar') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre de la Cancha *
                </label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                    placeholder="Ej: Cancha A"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo">
                    Tipo de Cancha *
                </label>
                <select name="tipo" id="tipo" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tipo') border-red-500 @enderror">
                    <option value="">Seleccione un tipo</option>
                    <option value="Fútbol 5" {{ old('tipo') == 'Fútbol 5' ? 'selected' : '' }}>Fútbol 5</option>
                    <option value="Fútbol 7" {{ old('tipo') == 'Fútbol 7' ? 'selected' : '' }}>Fútbol 7</option>
                    <option value="Fútbol 11" {{ old('tipo') == 'Fútbol 11' ? 'selected' : '' }}>Fútbol 11</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="precio_hora">
                    Precio por Hora * ($)
                </label>
                <input type="number" name="precio_hora" id="precio_hora" value="{{ old('precio_hora') }}" 
                    step="0.01" min="0" required placeholder="250.00"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('precio_hora') border-red-500 @enderror">
                @error('precio_hora')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="estado">
                    Estado *
                </label>
                <select name="estado" id="estado" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estado') border-red-500 @enderror">
                    <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                </select>
                @error('estado')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                    Descripción
                </label>
                <textarea name="descripcion" id="descripcion" rows="3"
                    placeholder="Características de la cancha..."
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descripcion') }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cancha
                </button>
                <a href="{{ route('canchas.listado') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
