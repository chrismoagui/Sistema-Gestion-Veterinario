@extends('layouts.app')
@section('title', 'Cliente: ' . $cliente->nombre_completo)
@section('page-title', 'Ficha del Cliente')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                <div style="width:72px;height:72px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.8rem;font-weight:700;color:var(--primary);">
                    {{ strtoupper(substr($cliente->nombre,0,1).substr($cliente->apellido,0,1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $cliente->nombre_completo }}</h5>
                <p class="text-muted small">CC: {{ $cliente->cedula }}</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    <a href="{{ route('pacientes.create', ['cliente_id' => $cliente->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus me-1"></i>Mascota
                    </a>
                </div>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Teléfono</small>
                    <p class="mb-0 small fw-semibold">{{ $cliente->telefono }}</p>
                </div>
                @if($cliente->email)
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Email</small>
                    <p class="mb-0 small fw-semibold">{{ $cliente->email }}</p>
                </div>
                @endif
                @if($cliente->direccion)
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Dirección</small>
                    <p class="mb-0 small fw-semibold">{{ $cliente->direccion }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Mascotas -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-heart me-2"></i>Mascotas ({{ $cliente->pacientes->count() }})</span>
                <a href="{{ route('pacientes.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
            <div class="card-body">
                @forelse($cliente->pacientes->where('activo', true) as $paciente)
                <a href="{{ route('pacientes.show', $paciente) }}"
                   class="d-flex align-items-center gap-3 p-2 rounded text-decoration-none text-dark mb-2"
                   style="background:#f8f9fa;">
                    <span style="font-size:1.8rem;">{{ $paciente->especie === 'Perro' ? '🐶' : ($paciente->especie === 'Gato' ? '🐱' : '🐾') }}</span>
                    <div>
                        <div class="fw-semibold">{{ $paciente->nombre }}</div>
                        <small class="text-muted">{{ $paciente->especie }} {{ $paciente->raza ? '· '.$paciente->raza : '' }} · {{ $paciente->edad }}</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </a>
                @empty
                <p class="text-muted text-center py-3">Sin mascotas registradas.</p>
                @endforelse
            </div>
        </div>

        <!-- Facturas -->
        <div class="card">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Facturas</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Número</th><th>Fecha</th><th>Total</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                        @forelse($cliente->facturas->sortByDesc('fecha_emision') as $factura)
                        <tr>
                            <td class="small fw-semibold">{{ $factura->numero_factura }}</td>
                            <td class="small">{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                            <td class="small">${{ number_format($factura->total, 2) }}</td>
                            <td><span class="badge bg-{{ $factura->estado === 'pagada' ? 'success' : ($factura->estado === 'anulada' ? 'secondary' : 'warning') }}">{{ ucfirst($factura->estado) }}</span></td>
                            <td><a href="{{ route('facturas.show', $factura) }}" class="btn btn-sm btn-outline-primary">Ver</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin facturas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
