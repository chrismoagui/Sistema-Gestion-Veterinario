@extends('layouts.app')
@section('title', 'Veterinarios')
@section('page-title', 'Gestión de Veterinarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-badge me-2"></i>Equipo Médico</span>
        <a href="{{ route('veterinarios.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Veterinario
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Veterinario</th><th>Especialidad</th><th>Licencia</th><th>Horario</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($veterinarios as $vet)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary);font-size:.8rem;">
                                {{ strtoupper(substr($vet->user->name,0,2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $vet->user->name }}</div>
                                <small class="text-muted">{{ $vet->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $vet->especialidad }}</td>
                    <td class="small text-muted">{{ $vet->numero_licencia }}</td>
                    <td class="small">{{ substr($vet->horario_inicio,0,5) }} — {{ substr($vet->horario_fin,0,5) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('veterinarios.show', $vet) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('veterinarios.edit', $vet) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No hay veterinarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($veterinarios->hasPages())
    <div class="card-footer">{{ $veterinarios->links() }}</div>
    @endif
</div>
@endsection
