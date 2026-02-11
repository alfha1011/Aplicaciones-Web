<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo
     */
    protected $table = 'canchas';

    /**
     * Los atributos que son asignables en masa
     */
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
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'precio_hora' => 'decimal:2',
    ];

    /**
     * Scope para obtener solo canchas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    /**
     * Scope para obtener canchas con coordenadas
     */
    public function scopeConCoordenadas($query)
    {
        return $query->whereNotNull('latitud')
                    ->whereNotNull('longitud');
    }

    /**
     * Scope para obtener canchas en una ciudad específica
     */
    public function scopeEnCiudad($query, $ciudad)
    {
        return $query->where('ciudad', 'like', '%' . $ciudad . '%');
    }

    /**
     * Obtener URL de Google Maps
     */
    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitud},{$this->longitud}";
        }
        return null;
    }

    /**
     * Obtener URL de direcciones en Google Maps
     */
    public function getGoogleMapsDirectionsUrlAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->latitud},{$this->longitud}";
        }
        return null;
    }

    /**
     * Verificar si la cancha tiene coordenadas GPS
     */
    public function tieneCoordenadas()
    {
        return !empty($this->latitud) && !empty($this->longitud);
    }

    /**
     * Calcular distancia a un punto usando fórmula de Haversine
     * 
     * @param float $latitud
     * @param float $longitud
     * @return float Distancia en kilómetros
     */
    public function calcularDistancia($latitud, $longitud)
    {
        if (!$this->tieneCoordenadas()) {
            return null;
        }

        $R = 6371; // Radio de la Tierra en km

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

    /**
     * Formatear distancia para lectura humana
     */
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

    /**
     * Obtener coordenadas como array
     */
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

    /**
     * Obtener dirección completa formateada
     */
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

    /**
     * Accessor para el estado de activación
     */
    public function getEstadoTextoAttribute()
    {
        return $this->activo ? 'Activa' : 'Inactiva';
    }

    /**
     * Accessor para clase CSS según estado
     */
    public function getEstadoClaseAttribute()
    {
        return $this->activo ? 'text-green-600' : 'text-red-600';
    }
}