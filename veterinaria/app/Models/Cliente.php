<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'cedula', 'telefono', 'email', 'direccion', 'activo'
    ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
