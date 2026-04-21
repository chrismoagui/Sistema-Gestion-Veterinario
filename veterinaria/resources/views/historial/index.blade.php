@extends('layouts.app')
@section('title', 'Historial Médico')
@section('page-title', 'Historial Médico')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-medical me-2"></i>Registros de Historial</span>
        <a href="{{ route('historial.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Registro
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="paciente_id" class="form-select form-select-sm">
                    <option value="">Todos los pacientes</option>
                    @foreach($pacientes as $p)
                    <option value="{{ $p->id }}" {{ request('paciente_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}" placeholder="Desde">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}" placeholder="Hasta">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm">Filtrar</button>
                <a href="{{ route('historial.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Fecha</th><th>Paciente</th><th>Diagnóstico</th><th>Veterinario</th><th>Próx. cita</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($historiales as $hist)
                <tr>
                    <td class="small fw-semibold">{{ $hist->fecha_consulta->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('pacientes.show', $hist->paciente) }}" class="text-decoration-none fw-semibold">
                            {{ $hist->paciente->nombre }}
                        </a>
                        <div class="text-muted small">{{ $hist->paciente->cliente->nombre_completo }}</div>
                    </td>
                    <td class="small">{{ Str::limit($hist->diagnostico, 60) }}</td>
                    <td class="small">{{ $hist->veterinario->user->name }}</td>
                    <td class="small">
                        @if($hist->proxima_consulta)
                            <span class="{{ $hist->proxima_consulta->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ $hist->proxima_consulta->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('historial.show', $hist) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('historial.edit', $hist) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No se encontraron registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($historiales->hasPages())
    <div class="card-footer">{{ $historiales->links() }}</div>
    @endif
</div>
@endsection
