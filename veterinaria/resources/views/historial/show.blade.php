@extends('layouts.app')
@section('title', 'Historial Médico')
@section('page-title', 'Detalle de Historial Médico')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-file-medical me-2"></i>Historia Clínica #{{ $historial->id }}</span>
                <span class="text-muted small">{{ $historial->fecha_consulta->format('d/m/Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Diagnóstico</label>
                        <p class="mt-1">{{ $historial->diagnostico }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Tratamiento</label>
                        <p class="mt-1">{{ $historial->tratamiento }}</p>
                    </div>
                    @if($historial->medicamentos)
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Medicamentos</label>
                        <p class="mt-1">{{ $historial->medicamentos }}</p>
                    </div>
                    @endif
                    @if($historial->examenes_realizados)
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase">Exámenes realizados</label>
                        <p class="mt-1">{{ $historial->examenes_realizados }}</p>
                    </div>
                    @endif
                    @if($historial->recomendaciones)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold text-uppercase">Recomendaciones</label>
                        <div class="alert alert-light border mt-1">{{ $historial->recomendaciones }}</div>
                    </div>
                    @endif
                    @if($historial->notas_adicionales)
                    <div class="col-12">
                        <label class="text-muted small fw-semibold text-uppercase">Notas adicionales</label>
                        <p class="mt-1 text-muted">{{ $historial->notas_adicionales }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('historial.edit', $historial) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('historial.reporte', $historial->paciente) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-earmark-text me-1"></i>Reporte del Paciente
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-heart me-2"></i>Paciente</div>
            <div class="card-body">
                <p class="fw-semibold mb-1">
                    <a href="{{ route('pacientes.show', $historial->paciente) }}" class="text-decoration-none">
                        {{ $historial->paciente->nombre }}
                    </a>
                </p>
                <p class="text-muted small mb-1">{{ $historial->paciente->especie }} · {{ $historial->paciente->raza }}</p>
                <p class="text-muted small mb-0">Dueño: {{ $historial->paciente->cliente->nombre_completo }}</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-activity me-2"></i>Signos Vitales</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 text-center">
                        <div class="fw-bold fs-5 text-primary">{{ $historial->peso_consulta ? $historial->peso_consulta . ' kg' : 'N/D' }}</div>
                        <small class="text-muted">Peso</small>
                    </div>
                    <div class="col-6 text-center">
                        <div class="fw-bold fs-5 text-warning">{{ $historial->temperatura ? $historial->temperatura . ' °C' : 'N/D' }}</div>
                        <small class="text-muted">Temperatura</small>
                    </div>
                </div>
            </div>
        </div>

        @if($historial->proxima_consulta)
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-calendar-check fs-3 text-success"></i>
                <div>
                    <div class="text-muted small">Próxima consulta</div>
                    <div class="fw-semibold">{{ $historial->proxima_consulta->format('d/m/Y') }}</div>
                    <div class="text-muted small">{{ $historial->proxima_consulta->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
