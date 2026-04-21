<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'nombre', 'especie', 'raza', 'sexo',
        'fecha_nacimiento', 'peso', 'color', 'microchip',
        'esterilizado', 'alergias', 'observaciones', 'activo'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'esterilizado' => 'boolean',
        'activo' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historiales()
    {
        return $this->hasMany(HistorialMedico::class);
    }

    public function getEdadAttribute(): string
    {
        if (!$this->fecha_nacimiento) return 'Desconocida';
        $años = $this->fecha_nacimiento->diffInYears(now());
        $meses = $this->fecha_nacimiento->diffInMonths(now()) % 12;
        if ($años > 0) return "{$años} año(s) {$meses} mes(es)";
        return "{$meses} mes(es)";
    }
}
