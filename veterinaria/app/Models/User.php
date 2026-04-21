<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'rol', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function veterinario()
    {
        return $this->hasOne(Veterinario::class);
    }

    public function citasRegistradas()
    {
        return $this->hasMany(Cita::class, 'registrado_por');
    }

    public function facturasGeneradas()
    {
        return $this->hasMany(Factura::class, 'generado_por');
    }

    public function esVeterinario(): bool
    {
        return $this->rol === 'veterinario';
    }

    public function esRecepcionista(): bool
    {
        return $this->rol === 'recepcionista';
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }
}
