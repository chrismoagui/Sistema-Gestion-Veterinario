@extends('layouts.app')
@section('title', 'Editar Cita')
@section('page-title', 'Editar Cita')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Editar Cita #{{ $cita->id }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('citas.update', $cita) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        @foreach($pacientes as $p)
                        <option value="{{ $p->id }}" {{ $cita->paciente_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }} — {{ $p->cliente->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Veterinario *</label>
                    <select name="veterinario_id" class="form-select" required>
                        @foreach($veterinarios as $v)
                        <option value="{{ $v->id }}" {{ $cita->veterinario_id == $v->id ? 'selected' : '' }}>
                            {{ $v->user->name }} — {{ $v->especialidad }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Fecha y Hora *</label>
                    <input type="datetime-local" name="fecha_hora" class="form-control"
                           value="{{ old('fecha_hora', $cita->fecha_hora->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Duración (min) *</label>
                    <select name="duracion_minutos" class="form-select" required>
                        @foreach([15,30,45,60,90,120] as $d)
                        <option value="{{ $d }}" {{ $cita->duracion_minutos == $d ? 'selected' : '' }}>{{ $d }} min</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['programada','confirmada','en_curso','completada','cancelada','no_asistio'] as $est)
                        <option value="{{ $est }}" {{ $cita->estado == $est ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$est)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['consulta','vacunacion','cirugia','control','urgencia','otro'] as $tipo)
                        <option value="{{ $tipo }}" {{ $cita->tipo == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Motivo de consulta *</label>
                    <textarea name="motivo_consulta" class="form-control" rows="3" required>{{ old('motivo_consulta', $cita->motivo_consulta) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $cita->observaciones) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Motivo de cancelación</label>
                    <textarea name="motivo_cancelacion" class="form-control" rows="2">{{ old('motivo_cancelacion', $cita->motivo_cancelacion) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('citas.show', $cita) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
