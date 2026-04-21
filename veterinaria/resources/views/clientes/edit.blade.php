@extends('layouts.app')
@section('title', 'Editar Cliente')
@section('page-title', 'Editar Cliente')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Editar: {{ $cliente->nombre_completo }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Apellido *</label>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $cliente->apellido) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cédula / Documento *</label>
                    <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $cliente->cedula) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Dirección</label>
                    <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $cliente->direccion) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
