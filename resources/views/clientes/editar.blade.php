@extends('layouts.app')

@section('titulo', 'Editar Cliente')

@section('contenido-principal')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-6">Editar Cliente</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.actualizar', $cliente->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">ID</label>
            <input type="text"
                   value="{{ $cliente->id }}"
                   disabled
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2 shadow-xs opacity-70">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Nombre</label>
            <input type="text"
                   name="nombre"
                   value="{{ old('nombre', $cliente->nombre) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Apellido</label>
            <input type="text"
                   name="apellido"
                   value="{{ old('apellido', $cliente->apellido) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $cliente->email) }}"
                   required
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Teléfono</label>
            <input type="text"
                   name="telefono"
                   value="{{ old('telefono', $cliente->telefono) }}"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">Dirección</label>
            <input type="text"
                   name="direccion"
                   value="{{ old('direccion', $cliente->direccion) }}"
                   class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
        </div>

        <div>
            <label class="block mb-2.5 text-sm font-medium text-heading">
                Ubicación (haz clic en el mapa para cambiar)
            </label>
            <div id="map-editar" class="w-full h-64 rounded border border-gray-300 mb-2"></div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-gray-500 text-xs mb-1">Latitud</label>
                    <input type="text" name="latitud" id="latitud"
                        value="{{ old('latitud', $cliente->latitud) }}"
                        readonly placeholder="Click en el mapa..."
                        class="border rounded w-full py-2 px-3 text-gray-600 bg-gray-50 text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-500 text-xs mb-1">Longitud</label>
                    <input type="text" name="longitud" id="longitud"
                        value="{{ old('longitud', $cliente->longitud) }}"
                        readonly placeholder="Click en el mapa..."
                        class="border rounded w-full py-2 px-3 text-gray-600 bg-gray-50 text-sm">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Haz clic en el mapa para actualizar la ubicación.</p>
        </div>

        <div class="mb-4">
            <label class="block mb-2.5 text-sm font-medium text-heading">Foto Actual</label>
            @if($cliente->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/clientes/' . $cliente->imagen) }}"
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
                Actualizar Cliente
            </button>

            <a href="{{ route('clientes.listado') }}"
               class="px-4 py-2 border border-default-medium rounded-base hover:bg-neutral-secondary-medium">
                Cancelar
            </a>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const latInicial = {{ $cliente->latitud ?? 20.6597 }};
        const lngInicial = {{ $cliente->longitud ?? -103.3496 }};
        const tieneUbicacion = {{ $cliente->latitud ? 'true' : 'false' }};

        const map = L.map('map-editar').setView([latInicial, lngInicial], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = null;

        if (tieneUbicacion) {
            marker = L.marker([latInicial, lngInicial]).addTo(map);
            marker.bindPopup('Ubicación actual').openPopup();
        }

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

            marker.bindPopup('Nueva ubicación').openPopup();
        });
    });
</script>
@endsection