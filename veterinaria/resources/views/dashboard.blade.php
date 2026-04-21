@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0d6e6e,#0a9696);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-val">{{ $stats['citas_hoy'] }}</div>
                    <div class="stat-lbl">Citas hoy</div>
                </div>
                <i class="bi bi-calendar-day stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f4a261,#e76f51);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-val">{{ $stats['citas_pendientes'] }}</div>
                    <div class="stat-lbl">Citas pendientes</div>
                </div>
                <i class="bi bi-clock stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#457b9d,#1d3557);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-val">{{ $stats['total_pacientes'] }}</div>
                    <div class="stat-lbl">Pacientes activos</div>
                </div>
                <i class="bi bi-heart stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2d6a4f,#40916c);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-val">${{ number_format($stats['ingresos_mes'], 0, ',', '.') }}</div>
                    <div class="stat-lbl">Ingresos del mes</div>
                </div>
                <i class="bi bi-cash-stack stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Citas de hoy -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2 text-primary"></i>Citas de Hoy</span>
                <a href="{{ route('citas.index', ['fecha' => now()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                @if($citas_hoy->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                        No hay citas para hoy
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Veterinario</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas_hoy as $cita)
                            <tr>
                                <td><strong>{{ $cita->fecha_hora->format('H:i') }}</strong></td>
                                <td>
                                    <a href="{{ route('pacientes.show', $cita->paciente) }}" class="text-decoration-none fw-semibold">
                                        {{ $cita->paciente->nombre }}
                                    </a>
                                    <div class="text-muted small">{{ $cita->paciente->cliente->nombre_completo }}</div>
                                </td>
                                <td class="small">{{ $cita->veterinario->user->name }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst($cita->tipo) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst(str_replace('_',' ',$cita->estado)) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Próximas citas -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock me-2 text-warning"></i>Próximas Citas</span>
                <a href="{{ route('citas.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($proximas_citas->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar fs-1 d-block mb-2 opacity-25"></i>
                        Sin próximas citas
                    </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach($proximas_citas as $cita)
                    <a href="{{ route('citas.show', $cita) }}" class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="text-center" style="min-width:44px;background:var(--primary-light);border-radius:8px;padding:.4rem;">
                                <div style="font-size:.7rem;color:var(--primary);font-weight:600;">{{ $cita->fecha_hora->format('MMM') }}</div>
                                <div style="font-size:1.1rem;font-weight:700;color:var(--primary);">{{ $cita->fecha_hora->format('d') }}</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">{{ $cita->paciente->nombre }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $cita->fecha_hora->format('H:i') }} · {{ $cita->veterinario->user->name }}
                                </div>
                            </div>
                            <span class="badge bg-{{ $cita->estado_badge }} align-self-center">{{ ucfirst($cita->estado) }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
