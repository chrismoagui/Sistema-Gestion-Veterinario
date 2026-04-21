@extends('layouts.app')
@section('title', 'Pacientes')
@section('page-title', 'Gestión de Pacientes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-heart me-2"></i>Listado de Pacientes</span>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Paciente
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="buscar" class="form-control form-control-sm"
                       placeholder="Buscar por nombre, especie, dueño..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="especie" class="form-select form-select-sm">
                    <option value="">Todas las especies</option>
                    @foreach($especies as $e)
                        <option value="{{ $e }}" {{ request('especie') == $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary btn-sm">Buscar</button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Nombre</th><th>Especie / Raza</th><th>Dueño</th>
                    <th>Edad</th><th>Peso</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                <tr>
                    <td class="text-muted small">{{ $paciente->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                                {{ $paciente->especie === 'Perro' ? '🐶' : ($paciente->especie === 'Gato' ? '🐱' : '🐾') }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $paciente->nombre }}</div>
                                <small class="text-muted">{{ ucfirst($paciente->sexo ?? 'N/D') }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ $paciente->especie }}</span>
                        @if($paciente->raza)
                        <div class="text-muted small">{{ $paciente->raza }}</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clientes.show', $paciente->cliente) }}" class="text-decoration-none">
                            {{ $paciente->cliente->nombre_completo }}
                        </a>
                    </td>
                    <td class="small">{{ $paciente->edad }}</td>
                    <td class="small">{{ $paciente->peso ? $paciente->peso . ' kg' : 'N/D' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('citas.create', ['paciente_id' => $paciente->id]) }}" class="btn btn-sm btn-outline-success" title="Nueva cita"><i class="bi bi-calendar-plus"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No se encontraron pacientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pacientes->hasPages())
    <div class="card-footer">{{ $pacientes->links() }}</div>
    @endif
</div>
@endsection
