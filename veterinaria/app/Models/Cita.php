<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id', 'veterinario_id', 'registrado_por', 'fecha_hora',
        'duracion_minutos', 'tipo', 'estado', 'motivo_consulta',
        'observaciones', 'motivo_cancelacion'
    ];

    protected $casts = ['fecha_hora' => 'datetime'];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function historial()
    {
        return $this->hasOne(HistorialMedico::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'programada'  => 'warning',
            'confirmada'  => 'info',
            'en_curso'    => 'primary',
            'completada'  => 'success',
            'cancelada'   => 'danger',
            'no_asistio'  => 'secondary',
            default       => 'secondary',
        };
    }
}
