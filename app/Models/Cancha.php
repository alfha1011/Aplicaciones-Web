<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    protected $table = 'canchas';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'precio_hora',
        'latitud',
        'longitud',
        'ciudad',
        'estado',
        'codigo_postal',
        'pais',
        'activo',
        'descripcion',
        'capacidad',
        'tipo_pasto',
        'imagen1',  
        'imagen2',  
        'imagen3'  
    ];

    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'precio_hora' => 'decimal:2',
    ];

    // ... resto de tus métodos (scopes, getters, etc.) IGUAL
    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    public function scopeConCoordenadas($query)
    {
        return $query->whereNotNull('latitud')
                    ->whereNotNull('longitud');
    }

    public function scopeEnCiudad($query, $ciudad)
    {
        return $query->where('ciudad', 'like', '%' . $ciudad . '%');
    }

    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitud},{$this->longitud}";
        }
        return null;
    }

    public function getGoogleMapsDirectionsUrlAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->latitud},{$this->longitud}";
        }
        return null;
    }

    public function tieneCoordenadas()
    {
        return !empty($this->latitud) && !empty($this->longitud);
    }

    public function calcularDistancia($latitud, $longitud)
    {
        if (!$this->tieneCoordenadas()) {
            return null;
        }

        $R = 6371;
        $lat1 = deg2rad($this->latitud);
        $lon1 = deg2rad($this->longitud);
        $lat2 = deg2rad($latitud);
        $lon2 = deg2rad($longitud);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat/2) * sin($dLat/2) +
             cos($lat1) * cos($lat2) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $R * $c;
    }

    public function formatearDistancia($latitud, $longitud)
    {
        $distancia = $this->calcularDistancia($latitud, $longitud);
        
        if ($distancia === null) {
            return 'Distancia no disponible';
        }

        if ($distancia < 1) {
            return round($distancia * 1000) . ' metros';
        }

        return round($distancia, 2) . ' km';
    }

    public function getCoordenadasAttribute()
    {
        if ($this->tieneCoordenadas()) {
            return [
                'lat' => (float) $this->latitud,
                'lng' => (float) $this->longitud,
            ];
        }
        return null;
    }

    public function getDireccionCompletaAttribute()
    {
        $partes = array_filter([
            $this->direccion,
            $this->ciudad,
            $this->estado,
            $this->codigo_postal,
            $this->pais,
        ]);

        return implode(', ', $partes);
    }

    public function getEstadoTextoAttribute()
    {
        return $this->activo ? 'Activa' : 'Inactiva';
    }

    public function getEstadoClaseAttribute()
    {
        return $this->activo ? 'text-green-600' : 'text-red-600';
    }
}