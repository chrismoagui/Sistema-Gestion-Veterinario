@extends('layouts.app')
@section('title', 'Facturas')
@section('page-title', 'Facturación')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Listado de Facturas</span>
        <a href="{{ route('facturas.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nueva Factura
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    @foreach(['pendiente','pagada','anulada','vencida'] as $est)
                    <option value="{{ $est }}" {{ request('estado') == $est ? 'selected' : '' }}>{{ ucfirst($est) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="cliente_id" class="form-select form-select-sm">
                    <option value="">Todos los clientes</option>
                    @foreach($clientes as $c)
                    <option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}"></div>
            <div class="col-md-2"><input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}"></div>
            <div class="col-md-2 d-flex gap-1">
                <button class="btn btn-primary btn-sm">Filtrar</button>
                <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary btn-sm">X</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Número</th><th>Cliente</th><th>Fecha</th><th>Subtotal</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($facturas as $factura)
                <tr>
                    <td class="fw-semibold small">{{ $factura->numero_factura }}</td>
                    <td><a href="{{ route('clientes.show', $factura->cliente) }}" class="text-decoration-none small">{{ $factura->cliente->nombre_completo }}</a></td>
                    <td class="small">{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                    <td class="small">${{ number_format($factura->subtotal, 2) }}</td>
                    <td class="fw-semibold">${{ number_format($factura->total, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $factura->estado === 'pagada' ? 'success' : ($factura->estado === 'anulada' ? 'secondary' : ($factura->estado === 'vencida' ? 'danger' : 'warning')) }}">
                            {{ ucfirst($factura->estado) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No se encontraron facturas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($facturas->hasPages())
    <div class="card-footer">{{ $facturas->links() }}</div>
    @endif
</div>
@endsection
