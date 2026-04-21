<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Pago;
use App\Models\Cita;
use App\Models\Cliente;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['cliente', 'cita']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_emision', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_emision', '<=', $request->fecha_hasta);
        }

        $facturas = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();

        return view('facturas.index', compact('facturas', 'clientes'));
    }

    public function create(Request $request)
    {
        $cita_id  = $request->query('cita_id');
        $cita     = $cita_id ? Cita::with(['paciente.cliente'])->findOrFail($cita_id) : null;
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $citas    = Cita::with(['paciente.cliente'])
            ->where('estado', 'completada')
            ->whereDoesntHave('factura')
            ->get();

        return view('facturas.create', compact('cita', 'clientes', 'citas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id'           => 'required|exists:citas,id',
            'cliente_id'        => 'required|exists:clientes,id',
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after:fecha_emision',
            'descuento'         => 'nullable|numeric|min:0',
            'observaciones'     => 'nullable|string',
            'detalles'          => 'required|array|min:1',
            'detalles.*.descripcion'    => 'required|string',
            'detalles.*.cantidad'       => 'required|integer|min:1',
            'detalles.*.precio_unitario'=> 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($validated['detalles'] as $detalle) {
            $subtotal += $detalle['cantidad'] * $detalle['precio_unitario'];
        }

        $descuento = $validated['descuento'] ?? 0;
        $impuesto  = ($subtotal - $descuento) * 0.19; // IVA 19%
        $total     = $subtotal - $descuento + $impuesto;

        $factura = Factura::create([
            'numero_factura'    => Factura::generarNumero(),
            'cita_id'           => $validated['cita_id'],
            'cliente_id'        => $validated['cliente_id'],
            'generado_por'      => auth()->id(),
            'fecha_emision'     => $validated['fecha_emision'],
            'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
            'subtotal'          => $subtotal,
            'descuento'         => $descuento,
            'impuesto'          => $impuesto,
            'total'             => $total,
            'observaciones'     => $validated['observaciones'] ?? null,
        ]);

        foreach ($validated['detalles'] as $detalle) {
            DetalleFactura::create([
                'factura_id'      => $factura->id,
                'descripcion'     => $detalle['descripcion'],
                'cantidad'        => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal'        => $detalle['cantidad'] * $detalle['precio_unitario'],
            ]);
        }

        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Factura generada exitosamente.');
    }

    public function show(Factura $factura)
    {
        $factura->load(['cliente', 'cita.paciente', 'detalles', 'pagos.registradoPor', 'generadoPor']);
        return view('facturas.show', compact('factura'));
    }

    public function registrarPago(Request $request, Factura $factura)
    {
        $validated = $request->validate([
            'monto'         => 'required|numeric|min:0.01|max:' . $factura->saldo_pendiente,
            'metodo_pago'   => 'required|in:efectivo,tarjeta_debito,tarjeta_credito,transferencia,otro',
            'referencia'    => 'nullable|string',
            'fecha_pago'    => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['factura_id']     = $factura->id;
        $validated['registrado_por'] = auth()->id();

        Pago::create($validated);

        // Actualizar estado de factura
        if ($factura->fresh()->saldo_pendiente <= 0) {
            $factura->update(['estado' => 'pagada']);
        }

        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function anular(Factura $factura)
    {
        $factura->update(['estado' => 'anulada']);
        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Factura anulada.');
    }
}
