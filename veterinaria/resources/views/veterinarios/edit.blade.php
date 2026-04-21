@extends('layouts.app')
@section('title', 'Editar Veterinario')
@section('page-title', 'Editar Veterinario')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Editar: {{ $veterinario->user->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('veterinarios.update', $veterinario) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre completo *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $veterinario->user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $veterinario->user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Especialidad *</label>
                    <input type="text" name="especialidad" class="form-control"
                           value="{{ old('especialidad', $veterinario->especialidad) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $veterinario->telefono) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Número de licencia *</label>
                    <input type="text" name="numero_licencia" class="form-control"
                           value="{{ old('numero_licencia', $veterinario->numero_licencia) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Horario inicio</label>
                    <input type="time" name="horario_inicio" class="form-control"
                           value="{{ old('horario_inicio', substr($veterinario->horario_inicio,0,5)) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Horario fin</label>
                    <input type="time" name="horario_fin" class="form-control"
                           value="{{ old('horario_fin', substr($veterinario->horario_fin,0,5)) }}">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('veterinarios.show', $veterinario) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
