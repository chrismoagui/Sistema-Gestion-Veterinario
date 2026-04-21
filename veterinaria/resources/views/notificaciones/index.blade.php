@extends('layouts.app')
@section('title', 'Notificaciones')
@section('page-title', 'Gestión de Notificaciones')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell me-2"></i>Notificaciones</span>
        <a href="{{ route('notificaciones.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nueva Notificación
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="enviada" {{ request('estado') == 'enviada' ? 'selected' : '' }}>Enviada</option>
                    <option value="fallida" {{ request('estado') == 'fallida' ? 'selected' : '' }}>Fallida</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    @foreach(['recordatorio_cita','confirmacion_cita','cancelacion_cita','resultado_examen','vacuna_pendiente','personalizada'] as $t)
                    <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm">Filtrar</button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Tipo</th><th>Cliente</th><th>Canal</th><th>Asunto</th><th>Estado</th><th>Fecha</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($notificaciones as $noti)
                <tr>
                    <td><span class="badge bg-light text-dark small">{{ ucfirst(str_replace('_',' ',$noti->tipo)) }}</span></td>
                    <td class="small">{{ $noti->cliente->nombre_completo }}</td>
                    <td><span class="badge bg-info text-dark">{{ strtoupper($noti->canal) }}</span></td>
                    <td class="small">{{ Str::limit($noti->asunto, 50) }}</td>
                    <td>
                        <span class="badge bg-{{ $noti->estado === 'enviada' ? 'success' : ($noti->estado === 'fallida' ? 'danger' : 'warning') }}">
                            {{ ucfirst($noti->estado) }}
                        </span>
                    </td>
                    <td class="small">{{ $noti->fecha_envio ? $noti->fecha_envio->format('d/m/Y H:i') : ($noti->programada_para ? 'Prog: '.$noti->programada_para->format('d/m/Y H:i') : '—') }}</td>
                    <td>
                        @if($noti->estado === 'pendiente' || $noti->estado === 'fallida')
                        <form method="POST" action="{{ route('notificaciones.enviar', $noti) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success" title="Enviar ahora">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No hay notificaciones.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notificaciones->hasPages())
    <div class="card-footer">{{ $notificaciones->links() }}</div>
    @endif
</div>
@endsection
