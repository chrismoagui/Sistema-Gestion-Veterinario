<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;

    protected $table = 'detalle_facturas';

    protected $fillable = ['factura_id', 'descripcion', 'cantidad', 'precio_unitario', 'subtotal'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
