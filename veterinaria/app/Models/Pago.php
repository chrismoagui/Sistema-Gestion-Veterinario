<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id', 'monto', 'metodo_pago', 'referencia',
        'fecha_pago', 'registrado_por', 'observaciones'
    ];

    protected $casts = ['fecha_pago' => 'datetime'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
