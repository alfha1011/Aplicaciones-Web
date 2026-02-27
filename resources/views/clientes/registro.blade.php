@extends('layouts.app')

@section('titulo', 'Registro de Cliente')

@section('contenido-principal')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Registrar Nuevo Cliente</h1>

        <form action="{{ route('clientes.guardar') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre *
                </label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido">
                    Apellido *
                </label>
                <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('apellido') border-red-500 @enderror">
                @error('apellido')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email *
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono">
                    Teléfono *
                </label>
                <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('telefono') border-red-500 @enderror">
                @error('telefono')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="direccion">
                    Dirección
                </label>
                <textarea name="direccion" id="direccion" rows="3"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('direccion') }}</textarea>
            </div>

            {{-- Mapa para elegir ubicación --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Ubicación (haz clic en el mapa para marcar)
                </label>
                <div id="map-registro" class="w-full h-64 rounded border border-gray-300 mb-2"></div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-gray-500 text-xs mb-1">Latitud</label>
                        <input type="text" name="latitud" id="latitud" value="{{ old('latitud') }}"
                            readonly placeholder="Click en el mapa..."
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 bg-gray-50 text-sm leading-tight">
                    </div>
                    <div class="flex-1">
                        <label class="block text-gray-500 text-xs mb-1">Longitud</label>
                        <input type="text" name="longitud" id="longitud" value="{{ old('longitud') }}"
                            readonly placeholder="Click en el mapa..."
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 bg-gray-50 text-sm leading-tight">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Opcional. Haz clic en el mapa para seleccionar la ubicación del cliente.</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="imagen">
                    Foto de Perfil
                </label>
                <input type="file" name="imagen" id="imagen" accept="image/*"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('imagen') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Máximo 2MB.</p>
                @error('imagen')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Cliente
                </button>
                <a href="{{ route('clientes.listado') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const map = L.map('map-registro').setView([20.6597, -103.3496], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = null;

        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitud').value  = lat;
            document.getElementById('longitud').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            marker.bindPopup('Ubicación seleccionada').openPopup();
        });
    });
</script>
@endsection