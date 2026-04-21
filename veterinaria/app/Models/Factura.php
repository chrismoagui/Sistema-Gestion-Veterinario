<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_factura', 'cita_id', 'cliente_id', 'generado_por',
        'fecha_emision', 'fecha_vencimiento', 'subtotal', 'descuento',
        'impuesto', 'total', 'estado', 'observaciones', 'enviada_correo'
    ];

    protected $casts = [
        'fecha_emision'    => 'date',
        'fecha_vencimiento'=> 'date',
        'enviada_correo'   => 'boolean',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function generadoPor()
    {
        return $this->belongsTo(User::class, 'generado_por');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getTotalPagadoAttribute(): float
    {
        return $this->pagos->sum('monto');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, $this->total - $this->total_pagado);
    }

    public static function generarNumero(): string
    {
        $ultimo = static::latest()->first();
        $numero = $ultimo ? (int) substr($ultimo->numero_factura, -6) + 1 : 1;
        return 'FAC-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}
