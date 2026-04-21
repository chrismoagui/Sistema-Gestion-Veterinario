@extends('layouts.app')
@section('title', 'Veterinario')
@section('page-title', 'Perfil del Veterinario')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div style="width:72px;height:72px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.6rem;font-weight:700;color:var(--primary);">
                    {{ strtoupper(substr($veterinario->user->name,0,2)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $veterinario->user->name }}</h5>
                <p class="text-muted small mb-1">{{ $veterinario->especialidad }}</p>
                <p class="text-muted small mb-3">Lic. {{ $veterinario->numero_licencia }}</p>
                <a href="{{ route('veterinarios.edit', $veterinario) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Email</small>
                    <p class="mb-0 small">{{ $veterinario->user->email }}</p>
                </div>
                @if($veterinario->telefono)
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Teléfono</small>
                    <p class="mb-0 small">{{ $veterinario->telefono }}</p>
                </div>
                @endif
                <div class="list-group-item border-0 py-2">
                    <small class="text-muted">Horario</small>
                    <p class="mb-0 small fw-semibold">{{ substr($veterinario->horario_inicio,0,5) }} — {{ substr($veterinario->horario_fin,0,5) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar-check me-2"></i>Últimas Citas</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Fecha</th><th>Paciente</th><th>Tipo</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                        @forelse($veterinario->citas->sortByDesc('fecha_hora') as $cita)
                        <tr>
                            <td class="small">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                            <td class="small fw-semibold">{{ $cita->paciente->nombre ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark small">{{ ucfirst($cita->tipo) }}</span></td>
                            <td><span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span></td>
                            <td><a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-outline-primary">Ver</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted small">Sin citas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
