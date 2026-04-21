@extends('layouts.app')
@section('title', 'Editar Historial')
@section('page-title', 'Editar Historial Médico')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Editar Historial #{{ $historial->id }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('historial.update', $historial) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Fecha de consulta</label>
                    <input type="date" name="fecha_consulta" class="form-control"
                           value="{{ old('fecha_consulta', $historial->fecha_consulta->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Peso (kg)</label>
                    <input type="number" name="peso_consulta" class="form-control" step="0.01"
                           value="{{ old('peso_consulta', $historial->peso_consulta) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Temperatura (°C)</label>
                    <input type="number" name="temperatura" class="form-control" step="0.1"
                           value="{{ old('temperatura', $historial->temperatura) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Diagnóstico *</label>
                    <textarea name="diagnostico" class="form-control" rows="3" required>{{ old('diagnostico', $historial->diagnostico) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Tratamiento *</label>
                    <textarea name="tratamiento" class="form-control" rows="3" required>{{ old('tratamiento', $historial->tratamiento) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Medicamentos</label>
                    <textarea name="medicamentos" class="form-control" rows="3">{{ old('medicamentos', $historial->medicamentos) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Exámenes realizados</label>
                    <textarea name="examenes_realizados" class="form-control" rows="3">{{ old('examenes_realizados', $historial->examenes_realizados) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Recomendaciones</label>
                    <textarea name="recomendaciones" class="form-control" rows="2">{{ old('recomendaciones', $historial->recomendaciones) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Próxima consulta</label>
                    <input type="date" name="proxima_consulta" class="form-control"
                           value="{{ old('proxima_consulta', $historial->proxima_consulta?->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Notas adicionales</label>
                    <textarea name="notas_adicionales" class="form-control" rows="2">{{ old('notas_adicionales', $historial->notas_adicionales) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('historial.show', $historial) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
