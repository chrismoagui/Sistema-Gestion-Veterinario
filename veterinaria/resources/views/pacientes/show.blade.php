@extends('layouts.app')
@section('title', 'Paciente: ' . $paciente->nombre)
@section('page-title', 'Ficha del Paciente')

@section('content')
<div class="row g-3">
    <!-- Info principal -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                <div style="font-size:4rem;line-height:1;">
                    {{ $paciente->especie === 'Perro' ? '🐶' : ($paciente->especie === 'Gato' ? '🐱' : '🐾') }}
                </div>
                <h4 class="fw-bold mt-2 mb-0">{{ $paciente->nombre }}</h4>
                <p class="text-muted mb-3">{{ $paciente->especie }} {{ $paciente->raza ? '· ' . $paciente->raza : '' }}</p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    <a href="{{ route('citas.create', ['paciente_id' => $paciente->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-plus me-1"></i>Nueva Cita
                    </a>
                </div>
            </div>
            <div class="card-footer">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="fw-bold">{{ $paciente->peso ? $paciente->peso . ' kg' : 'N/D' }}</div>
                        <div class="text-muted" style="font-size:.7rem;">PESO</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ $paciente->edad }}</div>
                        <div class="text-muted" style="font-size:.7rem;">EDAD</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold">{{ ucfirst($paciente->sexo ?? 'N/D') }}</div>
                        <div class="text-muted" style="font-size:.7rem;">SEXO</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-person me-2"></i>Dueño</div>
            <div class="card-body">
                <p class="fw-semibold mb-1">
                    <a href="{{ route('clientes.show', $paciente->cliente) }}" class="text-decoration-none">
                        {{ $paciente->cliente->nombre_completo }}
                    </a>
                </p>
                <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $paciente->cliente->telefono }}</p>
                @if($paciente->cliente->email)
                <p class="text-muted small mb-0"><i class="bi bi-envelope me-1"></i>{{ $paciente->cliente->email }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs con historial y citas -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs border-0 px-3 pt-2" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info">Información</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-citas">
                            Citas <span class="badge bg-secondary ms-1">{{ $paciente->citas->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-historial">
                            Historial <span class="badge bg-secondary ms-1">{{ $paciente->historiales->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <!-- Tab Info -->
                <div class="tab-pane fade show active" id="tab-info">
                    <div class="row g-3">
                        @if($paciente->microchip)
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">MICROCHIP</label>
                            <p>{{ $paciente->microchip }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">ESTERILIZADO</label>
                            <p>{{ $paciente->esterilizado ? 'Sí' : 'No' }}</p>
                        </div>
                        @if($paciente->color)
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">COLOR</label>
                            <p>{{ $paciente->color }}</p>
                        </div>
                        @endif
                        @if($paciente->alergias)
                        <div class="col-12">
                            <label class="text-muted small fw-semibold">ALERGIAS</label>
                            <div class="alert alert-warning py-2 small">{{ $paciente->alergias }}</div>
                        </div>
                        @endif
                        @if($paciente->observaciones)
                        <div class="col-12">
                            <label class="text-muted small fw-semibold">OBSERVACIONES</label>
                            <p>{{ $paciente->observaciones }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tab Citas -->
                <div class="tab-pane fade" id="tab-citas">
                    @forelse($paciente->citas->sortByDesc('fecha_hora') as $cita)
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom">
                        <div class="text-center" style="min-width:50px;background:#f8f9fa;border-radius:8px;padding:.5rem;">
                            <div style="font-size:.65rem;color:#666;">{{ $cita->fecha_hora->format('M Y') }}</div>
                            <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">{{ $cita->fecha_hora->format('d') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold small">{{ ucfirst($cita->tipo) }} — {{ $cita->fecha_hora->format('H:i') }}</span>
                                <span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                            </div>
                            <p class="text-muted small mb-1">{{ Str::limit($cita->motivo_consulta, 80) }}</p>
                            <small class="text-muted">Dr. {{ $cita->veterinario->user->name }}</small>
                        </div>
                        <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">Sin citas registradas</div>
                    @endforelse
                </div>

                <!-- Tab Historial -->
                <div class="tab-pane fade" id="tab-historial">
                    @forelse($paciente->historiales->sortByDesc('fecha_consulta') as $hist)
                    <div class="py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold">{{ $hist->fecha_consulta->format('d/m/Y') }}</span>
                                <span class="text-muted small ms-2">Dr. {{ $hist->veterinario->user->name }}</span>
                            </div>
                            <a href="{{ route('historial.show', $hist) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        </div>
                        <p class="small mt-1 mb-1"><strong>Dx:</strong> {{ Str::limit($hist->diagnostico, 100) }}</p>
                        <p class="small text-muted mb-0"><strong>Tx:</strong> {{ Str::limit($hist->tratamiento, 100) }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">Sin historial médico</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
