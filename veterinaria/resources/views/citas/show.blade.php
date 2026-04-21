@extends('layouts.app')
@section('title', 'Detalle Cita #' . $cita->id)
@section('page-title', 'Detalle de Cita')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Cita #{{ $cita->id }}</span>
                <span class="badge bg-{{ $cita->estado_badge }} fs-6">{{ ucfirst(str_replace('_',' ',$cita->estado)) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">PACIENTE</label>
                        <p class="mb-0 fw-semibold">
                            <a href="{{ route('pacientes.show', $cita->paciente) }}">{{ $cita->paciente->nombre }}</a>
                        </p>
                        <small class="text-muted">{{ $cita->paciente->especie }} · {{ $cita->paciente->raza }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">DUEÑO</label>
                        <p class="mb-0">{{ $cita->paciente->cliente->nombre_completo }}</p>
                        <small class="text-muted">{{ $cita->paciente->cliente->telefono }}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold">FECHA Y HORA</label>
                        <p class="mb-0 fw-semibold">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold">VETERINARIO</label>
                        <p class="mb-0">{{ $cita->veterinario->user->name }}</p>
                        <small class="text-muted">{{ $cita->veterinario->especialidad }}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold">TIPO</label>
                        <p class="mb-0"><span class="badge bg-light text-dark">{{ ucfirst($cita->tipo) }}</span></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">MOTIVO DE CONSULTA</label>
                        <p class="mb-0">{{ $cita->motivo_consulta }}</p>
                    </div>
                    @if($cita->observaciones)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">OBSERVACIONES</label>
                        <p class="mb-0">{{ $cita->observaciones }}</p>
                    </div>
                    @endif
                    @if($cita->motivo_cancelacion)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold text-danger">MOTIVO CANCELACIÓN</label>
                        <p class="mb-0 text-danger">{{ $cita->motivo_cancelacion }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer d-flex gap-2 flex-wrap">
                @if(!in_array($cita->estado, ['completada','cancelada']))
                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    @if($cita->estado === 'confirmada' || $cita->estado === 'programada')
                    <a href="{{ route('historial.create', ['cita_id' => $cita->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-file-medical me-1"></i>Registrar Historial
                    </a>
                    @endif
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelarModal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar Cita
                    </button>
                @endif
                @if($cita->estado === 'completada' && !$cita->factura)
                    <a href="{{ route('facturas.create', ['cita_id' => $cita->id]) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-receipt me-1"></i>Generar Factura
                    </a>
                @endif
            </div>
        </div>

        @if($cita->historial)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-file-medical me-2"></i>Historial Médico</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">DIAGNÓSTICO</label>
                        <p>{{ $cita->historial->diagnostico }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold">TRATAMIENTO</label>
                        <p>{{ $cita->historial->tratamiento }}</p>
                    </div>
                </div>
                <a href="{{ route('historial.show', $cita->historial) }}" class="btn btn-outline-primary btn-sm">Ver completo</a>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($cita->factura)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Factura</div>
            <div class="card-body">
                <p class="fw-semibold">{{ $cita->factura->numero_factura }}</p>
                <p>Total: <strong>${{ number_format($cita->factura->total, 2) }}</strong></p>
                <span class="badge bg-{{ $cita->factura->estado === 'pagada' ? 'success' : 'warning' }}">
                    {{ ucfirst($cita->factura->estado) }}
                </span>
                <div class="mt-3">
                    <a href="{{ route('facturas.show', $cita->factura) }}" class="btn btn-outline-primary btn-sm w-100">
                        Ver Factura
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><i class="bi bi-bell me-2"></i>Notificaciones</div>
            <div class="card-body p-0">
                @forelse($cita->notificaciones as $noti)
                <div class="list-group-item border-0 py-2 px-3">
                    <div class="d-flex justify-content-between">
                        <small class="fw-semibold">{{ ucfirst(str_replace('_',' ',$noti->tipo)) }}</small>
                        <span class="badge bg-{{ $noti->estado === 'enviada' ? 'success' : ($noti->estado === 'fallida' ? 'danger' : 'warning') }} small">{{ $noti->estado }}</span>
                    </div>
                    <div class="text-muted" style="font-size:.72rem;">{{ $noti->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @empty
                <div class="p-3 text-center text-muted small">Sin notificaciones</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="bi bi-x-circle me-2"></i>Cancelar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('citas.cancelar', $cita) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motivo de cancelación *</label>
                        <textarea name="motivo_cancelacion" class="form-control" rows="3"
                                  placeholder="Indica el motivo..." required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
