<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    use HasFactory;

    protected $table = 'historiales_medicos';

    protected $fillable = [
        'cita_id', 'paciente_id', 'veterinario_id', 'fecha_consulta',
        'diagnostico', 'tratamiento', 'medicamentos', 'examenes_realizados',
        'recomendaciones', 'peso_consulta', 'temperatura',
        'proxima_consulta', 'notas_adicionales'
    ];

    protected $casts = [
        'fecha_consulta'   => 'date',
        'proxima_consulta' => 'date',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class);
    }
}
