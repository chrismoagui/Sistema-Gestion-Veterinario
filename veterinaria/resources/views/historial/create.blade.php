@extends('layouts.app')
@section('title', 'Registrar Historial')
@section('page-title', 'Registrar Historial Médico')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
    <div class="card-header"><i class="bi bi-file-medical me-2"></i>Historia Clínica</div>
    <div class="card-body">
        @if($cita)
        <div class="alert alert-info d-flex gap-3 align-items-center mb-4">
            <i class="bi bi-info-circle fs-4"></i>
            <div>
                <strong>Cita #{{ $cita->id }}</strong> —
                {{ $cita->paciente->nombre }} ({{ $cita->paciente->especie }}) —
                {{ $cita->fecha_hora->format('d/m/Y H:i') }} —
                Dr. {{ $cita->veterinario->user->name }}
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('historial.store') }}">
            @csrf
            @if($cita)
                <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
            @else
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cita asociada *</label>
                    <select name="cita_id" class="form-select" required>
                        <option value="">Seleccionar cita...</option>
                        @foreach(\App\Models\Cita::with(['paciente'])->where('estado','confirmada')->orWhere('estado','programada')->get() as $c)
                        <option value="{{ $c->id }}">Cita #{{ $c->id }} - {{ $c->paciente->nombre }} - {{ $c->fecha_hora->format('d/m/Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        @foreach($pacientes as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }} — {{ $p->cliente->nombre_completo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Fecha de consulta *</label>
                    <input type="date" name="fecha_consulta" class="form-control"
                           value="{{ old('fecha_consulta', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Peso en consulta (kg)</label>
                    <input type="number" name="peso_consulta" class="form-control" step="0.01"
                           value="{{ old('peso_consulta', $cita?->paciente?->peso) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Temperatura (°C)</label>
                    <input type="number" name="temperatura" class="form-control" step="0.1" min="30" max="45"
                           value="{{ old('temperatura') }}" placeholder="Ej: 38.5">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Diagnóstico *</label>
                    <textarea name="diagnostico" class="form-control" rows="3"
                              placeholder="Diagnóstico clínico detallado..." required>{{ old('diagnostico') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Tratamiento *</label>
                    <textarea name="tratamiento" class="form-control" rows="3"
                              placeholder="Plan de tratamiento indicado..." required>{{ old('tratamiento') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Medicamentos</label>
                    <textarea name="medicamentos" class="form-control" rows="3"
                              placeholder="Nombre, dosis, frecuencia, duración...">{{ old('medicamentos') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Exámenes realizados</label>
                    <textarea name="examenes_realizados" class="form-control" rows="3"
                              placeholder="Exámenes de laboratorio, radiografías, etc.">{{ old('examenes_realizados') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Recomendaciones</label>
                    <textarea name="recomendaciones" class="form-control" rows="2"
                              placeholder="Cuidados en casa, dieta, restricciones...">{{ old('recomendaciones') }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Próxima consulta</label>
                    <input type="date" name="proxima_consulta" class="form-control"
                           value="{{ old('proxima_consulta') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Notas adicionales</label>
                    <textarea name="notas_adicionales" class="form-control" rows="2">{{ old('notas_adicionales') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Historial
                </button>
                <a href="{{ route('historial.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
