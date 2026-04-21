<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'especialidad', 'telefono', 'numero_licencia',
        'horario_inicio', 'horario_fin', 'activo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historiales()
    {
        return $this->hasMany(HistorialMedico::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->user->name ?? '';
    }

    public function estaDisponible(string $fechaHora, int $duracionMinutos = 30): bool
    {
        $inicio = \Carbon\Carbon::parse($fechaHora);
        $fin = $inicio->copy()->addMinutes($duracionMinutos);

        return !$this->citas()
            ->whereIn('estado', ['programada', 'confirmada', 'en_curso'])
            ->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_hora', [$inicio, $fin->subMinute()])
                    ->orWhereRaw('DATE_ADD(fecha_hora, INTERVAL duracion_minutos MINUTE) > ?', [$inicio]);
            })->exists();
    }
}
