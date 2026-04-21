@extends('layouts.app')
@section('title', 'Nueva Cita')
@section('page-title', 'Registrar Nueva Cita')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header"><i class="bi bi-calendar-plus me-2"></i>Datos de la Cita</div>
    <div class="card-body">
        <form method="POST" action="{{ route('citas.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Paciente (Mascota) *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        @foreach($pacientes as $p)
                        <option value="{{ $p->id }}" {{ old('paciente_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }} — {{ $p->cliente->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Veterinario *</label>
                    <select name="veterinario_id" id="veterinario_id" class="form-select" required>
                        <option value="">Seleccionar veterinario...</option>
                        @foreach($veterinarios as $v)
                        <option value="{{ $v->id }}" {{ old('veterinario_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->user->name }} — {{ $v->especialidad }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Fecha y Hora *</label>
                    <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control"
                           value="{{ old('fecha_hora') }}" required min="{{ now()->format('Y-m-d\TH:i') }}">
                    <div id="disponibilidad-msg" class="form-text"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Duración (min) *</label>
                    <select name="duracion_minutos" class="form-select" required>
                        @foreach([15,30,45,60,90,120] as $d)
                        <option value="{{ $d }}" {{ old('duracion_minutos', 30) == $d ? 'selected' : '' }}>{{ $d }} min</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['consulta','vacunacion','cirugia','control','urgencia','otro'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Motivo de consulta *</label>
                    <textarea name="motivo_consulta" class="form-control" rows="3"
                              placeholder="Describe el motivo de la consulta..." required>{{ old('motivo_consulta') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"
                              placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Registrar Cita
                </button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
// Verificar disponibilidad al cambiar fecha/veterinario
function verificar() {
    const vet = document.getElementById('veterinario_id').value;
    const fecha = document.getElementById('fecha_hora').value;
    const msg = document.getElementById('disponibilidad-msg');
    if (!vet || !fecha) return;

    fetch(`{{ route('citas.disponibilidad') }}?veterinario_id=${vet}&fecha_hora=${fecha}&duracion_minutos=30`)
        .then(r => r.json())
        .then(data => {
            if (data.disponible) {
                msg.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Horario disponible</span>';
            } else {
                msg.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Veterinario no disponible en ese horario</span>';
            }
        });
}
document.getElementById('veterinario_id').addEventListener('change', verificar);
document.getElementById('fecha_hora').addEventListener('change', verificar);
</script>
@endpush
