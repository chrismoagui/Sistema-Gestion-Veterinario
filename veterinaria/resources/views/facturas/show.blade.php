@extends('layouts.app')
@section('title', 'Factura ' . $factura->numero_factura)
@section('page-title', 'Detalle de Factura')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3" id="factura-print">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">FACTURA</h4>
                        <h5 class="text-muted">{{ $factura->numero_factura }}</h5>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">VetSystem</div>
                        <small class="text-muted">Sistema de Gestión Veterinaria</small>
                        <div class="mt-1">
                            <span class="badge fs-6 bg-{{ $factura->estado === 'pagada' ? 'success' : ($factura->estado === 'anulada' ? 'secondary' : 'warning') }}">
                                {{ strtoupper($factura->estado) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">FACTURAR A</label>
                        <p class="fw-semibold mb-0">{{ $factura->cliente->nombre_completo }}</p>
                        <p class="small text-muted mb-0">CC: {{ $factura->cliente->cedula }}</p>
                        <p class="small text-muted mb-0">Tel: {{ $factura->cliente->telefono }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="text-muted small fw-semibold">FECHAS</label>
                        <p class="small mb-0">Emisión: <strong>{{ $factura->fecha_emision->format('d/m/Y') }}</strong></p>
                        @if($factura->fecha_vencimiento)
                        <p class="small mb-0">Vencimiento: <strong>{{ $factura->fecha_vencimiento->format('d/m/Y') }}</strong></p>
                        @endif
                        <p class="small mb-0">Paciente: <strong>{{ $factura->cita->paciente->nombre }}</strong></p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Descripción</th><th class="text-center">Cant.</th><th class="text-end">Precio Unit.</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($factura->detalles as $det)
                        <tr>
                            <td>{{ $det->descripcion }}</td>
                            <td class="text-center">{{ $det->cantidad }}</td>
                            <td class="text-end">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-end">${{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td class="text-end">${{ number_format($factura->subtotal, 2) }}</td></tr>
                        @if($factura->descuento > 0)
                        <tr><td colspan="3" class="text-end text-muted">Descuento</td><td class="text-end text-danger">-${{ number_format($factura->descuento, 2) }}</td></tr>
                        @endif
                        <tr><td colspan="3" class="text-end text-muted">IVA (19%)</td><td class="text-end">${{ number_format($factura->impuesto, 2) }}</td></tr>
                        <tr class="table-light"><td colspan="3" class="text-end fw-bold">TOTAL</td><td class="text-end fw-bold text-primary fs-5">${{ number_format($factura->total, 2) }}</td></tr>
                    </tfoot>
                </table>

                @if($factura->observaciones)
                <div class="alert alert-light border mt-3">
                    <small class="text-muted fw-semibold">OBSERVACIONES: </small>{{ $factura->observaciones }}
                </div>
                @endif
            </div>
            <div class="card-footer d-flex gap-2 flex-wrap">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
                @if($factura->estado !== 'anulada')
                <form method="POST" action="{{ route('facturas.anular', $factura) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('¿Anular esta factura?')">
                        <i class="bi bi-x-circle me-1"></i>Anular
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Resumen pagos -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-cash me-2"></i>Estado de Pago</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Total factura:</span>
                    <strong>${{ number_format($factura->total, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Total pagado:</span>
                    <strong class="text-success">${{ number_format($factura->total_pagado, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-2">
                    <span class="fw-semibold">Saldo pendiente:</span>
                    <strong class="{{ $factura->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($factura->saldo_pendiente, 2) }}
                    </strong>
                </div>
            </div>
            @if($factura->saldo_pendiente > 0 && $factura->estado !== 'anulada')
            <div class="card-footer">
                <button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#pagoModal">
                    <i class="bi bi-cash-coin me-1"></i>Registrar Pago
                </button>
            </div>
            @endif
        </div>

        <!-- Historial de pagos -->
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Pagos registrados</div>
            <div class="card-body p-0">
                @forelse($factura->pagos as $pago)
                <div class="list-group-item border-0 py-2 px-3">
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold small">${{ number_format($pago->monto, 2) }}</span>
                        <span class="text-muted" style="font-size:.75rem;">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="text-muted small">{{ ucfirst(str_replace('_',' ',$pago->metodo_pago)) }}
                        {{ $pago->referencia ? '· Ref: '.$pago->referencia : '' }}
                    </div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small">Sin pagos registrados</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Pago -->
<div class="modal fade" id="pagoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('facturas.pago', $factura) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Monto *</label>
                            <input type="number" name="monto" class="form-control" step="0.01"
                                   value="{{ $factura->saldo_pendiente }}" max="{{ $factura->saldo_pendiente }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Método de pago *</label>
                            <select name="metodo_pago" class="form-select" required>
                                @foreach(['efectivo','tarjeta_debito','tarjeta_credito','transferencia','otro'] as $m)
                                <option value="{{ $m }}">{{ ucfirst(str_replace('_',' ',$m)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Referencia</label>
                            <input type="text" name="referencia" class="form-control" placeholder="Nro. transacción...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Fecha de pago *</label>
                            <input type="datetime-local" name="fecha_pago" class="form-control"
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
