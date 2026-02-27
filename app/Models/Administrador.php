<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'administradores';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'activo',
        'rol' 
    ];

    protected $hidden = [
        'password',
    ];

    public function esMaster()
    {
        return $this->rol === 'master';
    }

    public function esBase()
    {
        return $this->rol === 'base';
    }
}