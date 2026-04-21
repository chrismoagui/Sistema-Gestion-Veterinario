@extends('layouts.app')
@section('title', 'Citas')
@section('page-title', 'Gestión de Citas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-check me-2"></i>Listado de Citas</span>
        <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nueva Cita
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="date" name="fecha" class="form-control form-control-sm" value="{{ request('fecha') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    @foreach(['programada','confirmada','en_curso','completada','cancelada','no_asistio'] as $est)
                        <option value="{{ $est }}" {{ request('estado') == $est ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$est)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="veterinario_id" class="form-select form-select-sm">
                    <option value="">Todos los veterinarios</option>
                    @foreach($veterinarios as $v)
                        <option value="{{ $v->id }}" {{ request('veterinario_id') == $v->id ? 'selected' : '' }}>{{ $v->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm">Filtrar</button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Fecha/Hora</th><th>Paciente</th><th>Dueño</th>
                    <th>Veterinario</th><th>Tipo</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($citas as $cita)
                <tr>
                    <td class="text-muted small">{{ $cita->id }}</td>
                    <td>
                        <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong>
                        <div class="text-muted small">{{ $cita->fecha_hora->format('H:i') }}</div>
                    </td>
                    <td>
                        <a href="{{ route('pacientes.show', $cita->paciente) }}" class="fw-semibold text-decoration-none">
                            {{ $cita->paciente->nombre }}
                        </a>
                        <div class="text-muted small">{{ $cita->paciente->especie }}</div>
                    </td>
                    <td class="small">{{ $cita->paciente->cliente->nombre_completo }}</td>
                    <td class="small">{{ $cita->veterinario->user->name }}</td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst($cita->tipo) }}</span></td>
                    <td><span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst(str_replace('_',' ',$cita->estado)) }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(!in_array($cita->estado, ['completada','cancelada']))
                            <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No se encontraron citas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($citas->hasPages())
    <div class="card-footer">{{ $citas->links() }}</div>
    @endif
</div>
@endsection
