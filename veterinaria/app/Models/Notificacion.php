<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'cita_id', 'cliente_id', 'tipo', 'canal', 'asunto',
        'mensaje', 'estado', 'fecha_envio', 'programada_para', 'error_detalle'
    ];

    protected $casts = [
        'fecha_envio'     => 'datetime',
        'programada_para' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
