@extends('layouts.app')
@section('title', 'Nuevo Veterinario')
@section('page-title', 'Registrar Veterinario')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-person-badge me-2"></i>Datos del Veterinario</div>
    <div class="card-body">
        <form method="POST" action="{{ route('veterinarios.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre completo *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Confirmar contraseña *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Especialidad *</label>
                    <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad') }}"
                           placeholder="Ej: Medicina General, Cirugía..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Número de licencia *</label>
                    <input type="text" name="numero_licencia" class="form-control" value="{{ old('numero_licencia') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Horario inicio</label>
                    <input type="time" name="horario_inicio" class="form-control" value="{{ old('horario_inicio', '08:00') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Horario fin</label>
                    <input type="time" name="horario_fin" class="form-control" value="{{ old('horario_fin', '18:00') }}">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Registrar
                </button>
                <a href="{{ route('veterinarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
